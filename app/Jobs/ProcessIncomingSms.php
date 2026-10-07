<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Message;
use App\Models\AiLog;
use App\Services\AiService;
use App\Services\SmsService;
use App\Services\LanguageDetector;

class ProcessIncomingSms implements ShouldQueue
{
    use Queueable;

    protected $from;
    protected $text;

    /**
     * Create a new job instance.
     */
    public function __construct($from, $text)
    {
        $this->from = $from;
        $this->text = $text;
    }

    /**
     * Execute the job.
     */
    public function handle(AiService $aiService, SmsService $smsService, \App\Services\ModerationService $moderationService): void
    {
        // 1. Find or create user
        $user = User::firstOrCreate(['phone_number' => $this->from]);
        Log::info("User " . $user->phone_number . " created or found");

        // 2. Store user message
        $inboundMessage = Message::create([
            'user_id' => $user->id,
            'direction' => 'inbound',
            'content' => $this->text,
        ]);
        Log::info("User " . $user->phone_number . " message stored");

        // Handle Banned Users
        if ($user->is_banned) {
            Log::info("User " . $user->phone_number . " is banned. Ignoring message.");
            return; // Silently ignore or send a ban message once?
        }

        // 3. Resolve Language
        $language = \App\Models\SystemSetting::where('key', 'primary_language')->value('value') ?? 'sw';
        
        if ($language === 'auto') {
            $detector = new LanguageDetector();
            $language = $detector->detect($this->text) ?? 'sw'; // fallback to Swahili if undetermined
            Log::info("Language auto-detected as: " . $language);
        } else {
            Log::info("Language resolved from settings as: " . $language);
        }

        // Moderation Check
        if ($moderationService->isAbusive($this->text)) {
            $user->increment('abuse_count');
            Log::warning("User " . $user->phone_number . " sent abusive content. Abuse count: " . $user->abuse_count);

            if ($user->abuse_count >= 3) {
                $user->update(['is_banned' => true]);
                $warningMsg = $moderationService->getBanMessage($language);
                Log::error("User " . $user->phone_number . " has been BANNED due to repeated abuse.");
            } else {
                $warningMsg = $moderationService->getWarningMessage($language, $user->abuse_count);
            }

            $smsService->send($this->from, $warningMsg);

            Message::create([
                'user_id' => $user->id,
                'direction' => 'outbound',
                'content' => $warningMsg,
            ]);

            return; // Stop further processing
        }

        // Check if AI is enabled
        $aiEnabled = \App\Models\SystemSetting::where('key', 'ai_enabled')->value('value') ?? '1';

        if ($aiEnabled == '0') {
            $maintenanceMsg = \App\Models\SystemSetting::where('key', 'ai_maintenance_message')->value('value') ?? 'System is currently undergoing maintenance. Please try again later.';
            Log::info("AI engine is disabled. Sending maintenance message.");
            $smsService->send($this->from, $maintenanceMsg);

            Message::create([
                'user_id' => $user->id,
                'direction' => 'outbound',
                'content' => $maintenanceMsg,
            ]);
            return;
        }

        // 4. Generate Contextual AI Response
        $aiResult = $aiService->generateContextualResponse($this->text, $language);
        Log::info("AI response generated: " . $aiResult['text']);

        $aiResponseText = $aiResult['text'];

        // Handle AI-triggered moderation (SAFETY block)
        if ($aiResponseText === 'BANNED_CONTENT_DETECTED') {
            $user->increment('abuse_count');
            Log::warning("AI flagged User " . $user->phone_number . " for unsafe content. Abuse count: " . $user->abuse_count);

            if ($user->abuse_count >= 3) {
                $user->update(['is_banned' => true]);
                $warningMsg = $moderationService->getBanMessage($language);
            } else {
                $warningMsg = $moderationService->getWarningMessage($language, $user->abuse_count);
            }

            $smsService->send($this->from, $warningMsg);

            Message::create([
                'user_id' => $user->id,
                'direction' => 'outbound',
                'content' => $warningMsg,
            ]);

            return;
        }

        // ---------------------------------------------------------------------------
        // Re-apply the shared short-code keyword before sending.
        // Africa's Talking requires the keyword prefix on outbound messages that
        // originate from a shared short code so the reply is routed correctly.
        // ---------------------------------------------------------------------------
        // $outboundText = 'HURU ' . $aiResponseText;
        $outboundText = $aiResponseText;

        // 5. Send SMS
        $smsService->send($this->from, $outboundText);
        Log::info("User " . $user->phone_number . " message sent");

        // 6. Store system message
        $outboundMessage = Message::create([
            'user_id' => $user->id,
            'direction' => 'outbound',
            'content' => $outboundText,
        ]);
        Log::info("User " . $user->phone_number . " message stored");

        // 7. Log AI interaction
        AiLog::create([
            'message_id' => $outboundMessage->id,
            'model' => $aiResult['model'] ?? 'gemini-2.5-flash',
            'prompt' => $this->text, // Simple log of user query
            'response' => $aiResponseText,
            'prompt_tokens' => $aiResult['tokens']['promptTokenCount'] ?? 0,
            'completion_tokens' => $aiResult['tokens']['candidatesTokenCount'] ?? 0,
            'total_tokens' => $aiResult['tokens']['totalTokenCount'] ?? 0,
        ]);
        Log::info("User " . $user->phone_number . " message logged");
    }
}
