<?php

namespace App\Services;

use App\Models\AiLog;
use App\Models\Message;
use App\Models\User;
use App\Services\Ai\LlmClient;
use App\Support\Settings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * One entry point for every channel. Takes a citizen and their text,
 * returns what to send back. The SMS job and the web controller both call
 * this, so behaviour (commands, moderation, memory, logging) never drifts
 * between channels.
 */
class ConversationService
{
    public const KIND_ANSWER = 'answer';
    public const KIND_COMMAND = 'command';
    public const KIND_MODERATION = 'moderation';
    public const KIND_MAINTENANCE = 'maintenance';
    public const KIND_BANNED = 'banned';
    public const KIND_IGNORED = 'ignored';
    public const KIND_RATE_LIMITED = 'rate_limited';
    public const KIND_ERROR = 'error';

    public function __construct(
        private readonly AiService $ai,
        private readonly ModerationService $moderation,
        private readonly LanguageDetector $detector,
        private readonly TopicClassifier $classifier,
        private readonly TanzaniaContext $tz,
    ) {
    }

    /**
     * @return array{kind:string, text:?string, inbound:?Message, outbound:?Message, category:?string, language:string}
     */
    public function handle(User $user, string $text, string $channel): array
    {
        $text = trim($text);
        $user->forceFill(['last_seen_at' => now()])->saveQuietly();

        $language = $this->resolveLanguage($user, $text);
        $result = ['kind' => self::KIND_IGNORED, 'text' => null, 'inbound' => null, 'outbound' => null, 'category' => null, 'language' => $language];

        if ($text === '') {
            return $result;
        }

        // 1. Commands (HELP, STOP, LUGHA, JINA, MKOA ...) never hit the model.
        if ($command = $this->handleCommand($user, $text, $language, $channel)) {
            $inbound = $this->store($user, Message::DIRECTION_IN, $channel, $text, $language, null);
            $outbound = $this->store($user, Message::DIRECTION_OUT, $channel, $command, $language, null);

            return array_merge($result, ['kind' => self::KIND_COMMAND, 'text' => $command, 'inbound' => $inbound, 'outbound' => $outbound]);
        }

        // 2. Opted-out SMS users are left alone until they send START/ANZA.
        if ($user->opted_out && $channel === Message::CHANNEL_SMS) {
            return $result;
        }

        // 3. Banned users get one short notice, nothing else.
        if ($user->is_banned) {
            $msg = $this->moderation->getBanMessage($language);

            return array_merge($result, ['kind' => self::KIND_BANNED, 'text' => $msg]);
        }

        // 4. Per-user rate limit.
        $limitKey = 'huru:ask:' . $user->id;
        if (RateLimiter::tooManyAttempts($limitKey, Settings::int('rate_limit_per_minute'))) {
            $msg = $language === 'en'
                ? 'You are sending questions too quickly. Please wait a minute and try again.'
                : 'Umetuma maswali mengi kwa haraka. Tafadhali subiri dakika moja kisha ujaribu tena.';

            return array_merge($result, ['kind' => self::KIND_RATE_LIMITED, 'text' => $msg]);
        }
        RateLimiter::hit($limitKey, 60);

        $classification = $this->classifier->classify($text);
        $category = $classification['category'];
        $result['category'] = $category;

        $inbound = $this->store($user, Message::DIRECTION_IN, $channel, $text, $language, $category);
        $result['inbound'] = $inbound;

        // 5. Word-list moderation with strikes.
        if ($this->moderation->isAbusive($text)) {
            $msg = $this->strike($user, $language);
            $outbound = $this->store($user, Message::DIRECTION_OUT, $channel, $msg, $language, $category);

            return array_merge($result, ['kind' => self::KIND_MODERATION, 'text' => $msg, 'outbound' => $outbound]);
        }

        // 6. Maintenance switch.
        if (! Settings::bool('ai_enabled')) {
            $msg = (string) Settings::get($language === 'en' ? 'ai_maintenance_message_en' : 'ai_maintenance_message_sw');
            $outbound = $this->store($user, Message::DIRECTION_OUT, $channel, $msg, $language, $category);

            return array_merge($result, ['kind' => self::KIND_MAINTENANCE, 'text' => $msg, 'outbound' => $outbound]);
        }

        // 7. Ask the engine with full context.
        $location = $this->tz->detectLocation($text);
        if ($location && ! $user->region) {
            $user->forceFill(['region' => $location['region']])->saveQuietly();
        }

        $answer = $this->ai->answer([
            'question' => $text,
            'language' => $language,
            'category' => $category,
            'channel' => $channel,
            'is_emergency' => $classification['is_emergency'],
            'location' => $location,
            'history' => $this->history($user, $inbound->id),
            'user_name' => $user->name,
            'user_region' => $user->region,
        ]);

        if ($answer['status'] === LlmClient::STATUS_SAFETY) {
            $msg = $this->strike($user, $language);
            $outbound = $this->store($user, Message::DIRECTION_OUT, $channel, $msg, $language, $category);
            $this->log($outbound, $answer, $category, $text);

            return array_merge($result, ['kind' => self::KIND_MODERATION, 'text' => $msg, 'outbound' => $outbound]);
        }

        if ($answer['status'] !== LlmClient::STATUS_OK) {
            $msg = $language === 'en'
                ? 'Sorry, the service could not answer right now. Please try again shortly.'
                : 'Samahani, huduma haikuweza kujibu kwa sasa. Tafadhali jaribu tena baada ya muda mfupi.';
            $outbound = $this->store($user, Message::DIRECTION_OUT, $channel, $msg, $language, $category);
            $this->log($outbound, $answer, $category, $text);
            Log::warning('Engine failure', ['user' => $user->id, 'status' => $answer['status'], 'error' => $answer['error']]);

            return array_merge($result, ['kind' => self::KIND_ERROR, 'text' => $msg, 'outbound' => $outbound]);
        }

        $outbound = $this->store($user, Message::DIRECTION_OUT, $channel, $answer['text'], $language, $category);
        $this->log($outbound, $answer, $category, $text);

        return array_merge($result, ['kind' => self::KIND_ANSWER, 'text' => $answer['text'], 'outbound' => $outbound]);
    }

    // ------------------------------------------------------------------ helpers

    public function resolveLanguage(User $user, string $text): string
    {
        if (in_array($user->preferred_language, ['sw', 'en'], true)) {
            // A stored preference wins unless the citizen clearly wrote the other language.
            $detected = $this->detector->detect($text);

            return $detected ?: $user->preferred_language;
        }

        $policy = (string) Settings::get('primary_language');
        if ($policy === 'sw' || $policy === 'en') {
            return $policy;
        }

        return $this->detector->detect($text) ?: 'sw';
    }

    /** Returns the reply for a service command, or null if the text is not a command. */
    private function handleCommand(User $user, string $text, string $language, string $channel): ?string
    {
        $upper = Str::upper(trim($text));
        $parts = preg_split('/\s+/', $upper, 2);
        $verb = $parts[0] ?? '';
        $arg = trim($parts[1] ?? '');
        $app = Settings::get('app_name');

        if (in_array($verb, ['HELP', 'MSAADA', 'MENU', '?'], true) && $arg === '') {
            return $this->helpText($language);
        }

        if (in_array($verb, ['STOP', 'ACHA', 'SITAKI', 'ONDOA'], true) && $arg === '') {
            $user->forceFill(['opted_out' => true])->saveQuietly();

            return $language === 'en'
                ? "You will no longer receive replies from {$app}. Send ANZA or START any time to resume."
                : "Hutapokea tena majibu kutoka {$app}. Tuma ANZA au START wakati wowote kuendelea.";
        }

        if (in_array($verb, ['START', 'ANZA', 'RUDI'], true) && $arg === '') {
            $user->forceFill(['opted_out' => false])->saveQuietly();

            return $language === 'en'
                ? "Welcome back to {$app}. Send any question to begin."
                : "Karibu tena {$app}. Tuma swali lolote kuanza.";
        }

        if (in_array($verb, ['LUGHA', 'LANG', 'LANGUAGE'], true)) {
            $choice = match (true) {
                in_array($arg, ['SW', 'KISWAHILI', 'SWAHILI'], true) => 'sw',
                in_array($arg, ['EN', 'ENGLISH', 'KIINGEREZA'], true) => 'en',
                $arg === '' || in_array($arg, ['AUTO', 'YOYOTE'], true) => null,
                default => false,
            };
            if ($choice === false) {
                return $language === 'en'
                    ? 'Send LUGHA SW for Swahili, LUGHA EN for English, or LUGHA AUTO to detect automatically.'
                    : 'Tuma LUGHA SW kwa Kiswahili, LUGHA EN kwa Kiingereza, au LUGHA AUTO kutambua yenyewe.';
            }
            $user->forceFill(['preferred_language' => $choice])->saveQuietly();

            return match ($choice) {
                'sw' => 'Sawa. Kuanzia sasa nitakujibu kwa Kiswahili.',
                'en' => 'Done. From now on I will reply in English.',
                default => $language === 'en' ? 'Done. I will follow the language you write in.' : 'Sawa. Nitafuata lugha unayoandika.',
            };
        }

        if (in_array($verb, ['JINA', 'NAME'], true) && $arg !== '' && strlen($arg) <= 40) {
            $name = Str::title(Str::lower($arg));
            $user->forceFill(['name' => $name])->saveQuietly();

            return $language === 'en' ? "Thank you, {$name}. I have saved your name." : "Asante, {$name}. Nimehifadhi jina lako.";
        }

        if (in_array($verb, ['MKOA', 'REGION'], true) && $arg !== '') {
            $loc = $this->tz->detectLocation($arg);
            if (! $loc) {
                return $language === 'en'
                    ? 'I could not recognise that region. Example: MKOA Mwanza'
                    : 'Sikutambua mkoa huo. Mfano: MKOA Mwanza';
            }
            $user->forceFill(['region' => $loc['region']])->saveQuietly();
            $label = Str::title($loc['region']);

            return $language === 'en'
                ? "Saved. I will prioritise services in {$label} when you ask for help nearby."
                : "Nimehifadhi. Nitatoa kipaumbele kwa huduma za {$label} utakapoomba msaada wa karibu.";
        }

        return null;
    }

    public function helpText(string $language): string
    {
        $app = Settings::get('app_name');
        $kw = Settings::get('sms_keyword');
        $code = Settings::get('sms_shortcode');

        if ($language === 'en') {
            return "{$app}: ask anything about life in Tanzania - law, health, farming, school subjects, government services, money, jobs. "
                . "SMS: send {$kw} followed by your question to {$code}. "
                . 'Commands: LUGHA SW/EN (language), JINA <name>, MKOA <region>, STOP to pause, START to resume.';
        }

        return "{$app}: uliza lolote kuhusu maisha Tanzania - sheria, afya, kilimo, masomo, huduma za serikali, fedha, ajira. "
            . "SMS: tuma {$kw} kisha swali lako kwenda {$code}. "
            . 'Amri: LUGHA SW/EN (lugha), JINA <jina lako>, MKOA <mkoa wako>, ACHA kusitisha, ANZA kuendelea.';
    }

    private function strike(User $user, string $language): string
    {
        $limit = Settings::int('abuse_strike_limit');
        $user->increment('abuse_count');
        $user->refresh();

        if ($user->abuse_count >= $limit) {
            $user->forceFill(['is_banned' => true])->saveQuietly();
            Log::warning('User banned for abuse', ['user' => $user->id]);

            return $this->moderation->getBanMessage($language);
        }

        return $this->moderation->getWarningMessage($language, $user->abuse_count, $limit);
    }

    /** Previous question/answer pairs for continuity, oldest first. */
    private function history(User $user, int $excludeMessageId): array
    {
        $turns = Settings::int('history_turns');
        if ($turns <= 0) {
            return [];
        }

        $recent = Message::query()
            ->where('user_id', $user->id)
            ->where('id', '<', $excludeMessageId)
            ->where('created_at', '>=', now()->subDays(2))
            ->whereNotNull('category')
            ->latest('id')
            ->take($turns * 2)
            ->get()
            ->reverse()
            ->values();

        $history = [];
        foreach ($recent as $m) {
            $history[] = [
                'role' => $m->direction === Message::DIRECTION_IN ? 'user' : 'model',
                'text' => Str::limit($m->content, 600, ''),
            ];
        }

        // Ensure we start with a user turn so the model sees valid alternation.
        while (! empty($history) && $history[0]['role'] !== 'user') {
            array_shift($history);
        }

        return $history;
    }

    private function store(User $user, string $direction, string $channel, string $content, string $language, ?string $category): Message
    {
        return Message::create([
            'user_id' => $user->id,
            'direction' => $direction,
            'channel' => $channel,
            'content' => $content,
            'language' => $language,
            'category' => $category,
        ]);
    }

    private function log(Message $outbound, array $answer, ?string $category, string $question): void
    {
        AiLog::create([
            'message_id' => $outbound->id,
            'model' => $answer['model'] ?? 'unknown',
            'category' => $category,
            'status' => $answer['status'] ?? 'error',
            'prompt' => $question . "\n\n--- SYSTEM ---\n" . Str::limit($answer['system'] ?? '', 12000, '...'),
            'response' => (string) ($answer['text'] ?? $answer['error'] ?? ''),
            'prompt_tokens' => $answer['tokens']['prompt'] ?? 0,
            'completion_tokens' => $answer['tokens']['completion'] ?? 0,
            'total_tokens' => $answer['tokens']['total'] ?? 0,
            'latency_ms' => $answer['latency_ms'] ?? 0,
        ]);
    }
}
