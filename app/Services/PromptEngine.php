<?php

namespace App\Services;

use App\Models\LocalResource;
use App\Models\PromptTemplate;
use App\Models\ReferenceContact;
use App\Support\Settings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Assembles the system instruction and conversation turns for the engine.
 *
 * Layers, in order:
 *   1. Persona for the detected topic and language (admin-editable template)
 *   2. Country context (date, currency, institutions, emergency numbers)
 *   3. Topic guidance (how this subject works in Tanzania)
 *   4. Citizen profile (name, region, detected location)
 *   5. Retrieved knowledge entries
 *   6. Local resources near the citizen
 *   7. National directory contacts relevant to the topic
 *   8. Output constraints for the channel (SMS vs web)
 */
class PromptEngine
{
    public function __construct(
        private readonly KnowledgeRetriever $retriever,
        private readonly TanzaniaContext $tz,
    ) {
    }

    /**
     * @param array{
     *   question:string, language:string, category:string, channel:string,
     *   is_emergency?:bool, location?:?array, history?:array<int,array{role:string,text:string}>,
     *   user_name?:?string, user_region?:?string
     * } $ctx
     * @return array{system:string, turns:array, knowledge:Collection, resources:Collection, contacts:Collection}
     */
    public function build(array $ctx): array
    {
        $lang = $ctx['language'] === 'en' ? 'en' : 'sw';
        $category = $ctx['category'] ?: TopicClassifier::GENERAL;
        $channel = $ctx['channel'] ?? 'web';
        $question = trim($ctx['question']);
        $location = $ctx['location'] ?? null;
        $isEmergency = (bool) ($ctx['is_emergency'] ?? false);

        $knowledge = $this->retriever->search($question, $lang, $category, Settings::int('knowledge_matches'));
        $resources = $this->findLocalResources($location, $category, $ctx['user_region'] ?? null);
        $contacts = $this->findContacts($category, $isEmergency);

        $sections = [];
        $sections[] = $this->persona($category, $lang);
        $sections[] = $this->tz->localeBlock($lang);
        $sections[] = ($lang === 'en' ? "TOPIC RULES ({$this->label($category, $lang)}):\n" : "KANUNI ZA MADA ({$this->label($category, $lang)}):\n")
            . $this->tz->categoryGuidance($category, $lang);
        $sections[] = $this->profileBlock($ctx, $location, $lang);
        $sections[] = $this->knowledgeBlock($knowledge, $lang);
        $sections[] = $this->resourcesBlock($resources, $location, $lang);
        $sections[] = $this->contactsBlock($contacts, $isEmergency, $lang);
        $sections[] = $this->constraints($lang, $channel, $isEmergency);

        $system = implode("\n\n", array_filter($sections));

        $turns = [];
        foreach ($ctx['history'] ?? [] as $turn) {
            if (! empty($turn['text'])) {
                $turns[] = ['role' => $turn['role'], 'text' => $turn['text']];
            }
        }
        $turns[] = ['role' => 'user', 'text' => $question];

        return [
            'system' => $system,
            'turns' => $turns,
            'knowledge' => $knowledge,
            'resources' => $resources,
            'contacts' => $contacts,
        ];
    }

    // ------------------------------------------------------------------ blocks

    private function persona(string $category, string $lang): string
    {
        $templates = Cache::remember('huru.templates.active', 3600, fn () => PromptTemplate::query()
            ->where('is_active', true)
            ->get()
            ->keyBy(fn (PromptTemplate $t) => "{$t->category}:{$t->language}"));

        $tpl = $templates["{$category}:{$lang}"]
            ?? $templates[TopicClassifier::GENERAL . ":{$lang}"]
            ?? null;

        $text = $tpl?->template ?: $this->defaultPersona($lang);

        return str_replace(
            ['{app_name}', '{category}'],
            [Settings::get('app_name'), $this->label($category, $lang)],
            $text
        );
    }

    private function defaultPersona(string $lang): string
    {
        return $lang === 'en'
            ? 'You are {app_name}, a trusted everyday helper for citizens of Tanzania. You answer any question, on any subject, with accurate, practical, locally relevant guidance. You speak as a knowledgeable, respectful Tanzanian neighbour would: clear, direct, warm, never condescending.'
            : 'Wewe ni {app_name}, msaidizi wa kuaminika wa kila siku kwa wananchi wa Tanzania. Unajibu swali lolote, la mada yoyote, kwa usahihi, kwa vitendo na kwa muktadha wa Tanzania. Unazungumza kama jirani Mtanzania mwenye maarifa na heshima: wazi, moja kwa moja, mkarimu, bila kudharau.';
    }

    private function profileBlock(array $ctx, ?array $location, string $lang): string
    {
        $lines = [];
        if (! empty($ctx['user_name'])) {
            $lines[] = ($lang === 'en' ? '- Name: ' : '- Jina: ') . $ctx['user_name'];
        }
        if ($location) {
            $place = array_filter([
                $location['area'] ? Str::title($location['area']) : null,
                $location['district'] ? Str::title($location['district']) : null,
                $location['region'] ? Str::title($location['region']) : null,
            ]);
            $lines[] = ($lang === 'en' ? '- Location mentioned in this question: ' : '- Mahali alipotaja kwenye swali hili: ') . implode(', ', $place);
        } elseif (! empty($ctx['user_region'])) {
            $lines[] = ($lang === 'en' ? '- Home region on file: ' : '- Mkoa wake uliohifadhiwa: ') . Str::title($ctx['user_region']);
        }
        if (empty($lines)) {
            return '';
        }

        return ($lang === 'en' ? "ABOUT THE CITIZEN:\n" : "KUHUSU MWANANCHI:\n") . implode("\n", $lines);
    }

    private function knowledgeBlock(Collection $knowledge, string $lang): string
    {
        if ($knowledge->isEmpty()) {
            return $lang === 'en'
                ? 'VERIFIED LOCAL KNOWLEDGE: none matched this question. Rely on your general knowledge of Tanzania and say clearly when something should be confirmed with the responsible office.'
                : 'MAARIFA YALIYOTHIBITISHWA: hakuna yaliyolingana na swali hili. Tumia maarifa yako ya jumla kuhusu Tanzania na sema wazi pale ambapo jambo linapaswa kuthibitishwa na ofisi husika.';
        }

        $out = $lang === 'en'
            ? "VERIFIED LOCAL KNOWLEDGE (prefer this over your own memory when they conflict):\n"
            : "MAARIFA YALIYOTHIBITISHWA (yape kipaumbele kuliko kumbukumbu yako yakipingana):\n";

        foreach ($knowledge as $e) {
            $out .= "[{$e->title}]\n" . trim($e->content) . "\n";
            if ($e->source) {
                $out .= ($lang === 'en' ? 'Source: ' : 'Chanzo: ') . $e->source . "\n";
            }
            $out .= "\n";
        }

        return trim($out);
    }

    private function resourcesBlock(Collection $resources, ?array $location, string $lang): string
    {
        if ($resources->isEmpty()) {
            return '';
        }

        $where = $location ? Str::title($location['district'] ?? $location['region']) : '';
        $out = $lang === 'en'
            ? "SERVICES NEAR THE CITIZEN" . ($where ? " ({$where})" : '') . " (mention the most relevant ones by name with their contact):\n"
            : "HUDUMA ZILIZO KARIBU NA MWANANCHI" . ($where ? " ({$where})" : '') . " (taja zinazofaa zaidi kwa jina na mawasiliano yake):\n";

        foreach ($resources as $r) {
            $out .= "- {$r->name} (" . (LocalResource::CATEGORIES[$r->category] ?? $r->category) . '; ' . Str::title($r->district ?: $r->region) . ")";
            $bits = array_filter([$r->location, $r->phone ? ('Simu/Phone: ' . $r->phone) : null, $r->email ? ('Email: ' . $r->email) : null]);
            if ($bits) {
                $out .= ': ' . implode('; ', $bits);
            }
            $out .= "\n";
        }

        return trim($out);
    }

    private function contactsBlock(Collection $contacts, bool $isEmergency, string $lang): string
    {
        if ($contacts->isEmpty()) {
            return '';
        }

        $out = $lang === 'en'
            ? "OFFICIAL NATIONAL CONTACTS (use only when they help the citizen act; do not pad ordinary answers with them):\n"
            : "MAWASILIANO RASMI YA KITAIFA (tumia tu pale yanapomsaidia mwananchi kuchukua hatua; usiyajaze kwenye majibu ya kawaida):\n";

        foreach ($contacts as $c) {
            $bits = array_filter([$c->phone, $c->alt_phone, $c->email, $c->website]);
            $out .= '- ' . $c->name . ($c->is_emergency ? ($lang === 'en' ? ' [EMERGENCY]' : ' [DHARURA]') : '')
                . ($bits ? ': ' . implode(' / ', $bits) : '')
                . ($c->description($lang) ? ' - ' . $c->description($lang) : '')
                . "\n";
        }

        if ($isEmergency) {
            $out .= $lang === 'en'
                ? "\nTHIS LOOKS LIKE AN EMERGENCY: put the correct emergency number and the single most important immediate action in the FIRST line of your reply."
                : "\nHILI LINAONEKANA KUWA DHARURA: weka namba sahihi ya dharura na hatua moja muhimu zaidi ya haraka kwenye MSTARI WA KWANZA wa jibu lako.";
        }

        return trim($out);
    }

    private function constraints(string $lang, string $channel, bool $isEmergency): string
    {
        $maxWords = $channel === 'sms' ? Settings::int('sms_max_words') : Settings::int('web_max_words');
        $signature = trim((string) Settings::get($lang === 'en' ? 'signature_en' : 'signature_sw'));

        if ($lang === 'en') {
            $rules = [
                'Reply ONLY in the language of the citizen\'s latest message (English here). If they switch language, switch with them.',
                "Keep it under {$maxWords} words. " . ($channel === 'sms'
                    ? 'This is an SMS: short sentences, the most useful fact first, no lists longer than 4 items.'
                    : 'Use short paragraphs; a brief numbered list is fine for steps.'),
                'Plain text only. No markdown, no asterisks, no emoji, no headings.',
                'No greetings, no "as an assistant", no repeating the question. Start with the answer.',
                'Be honest: if you do not know or the information may have changed (fees, officials, deadlines), say so and name the office or website that can confirm.',
                'Never invent phone numbers, prices, laws or names of officials. Use only numbers given in this instruction or well-established public ones.',
                'Give no medical dosages, no legal representation, no political campaigning, no religious judgement.',
                'If the citizen asked a follow-up, use the earlier conversation turns to keep continuity.',
            ];
            if ($isEmergency) {
                array_unshift($rules, 'EMERGENCY: first line must be the emergency number and the immediate action.');
            }
            $out = "OUTPUT RULES:\n- " . implode("\n- ", $rules);
            if ($signature !== '') {
                $out .= "\n- End with exactly this line: \"{$signature}\"";
            }

            return $out;
        }

        $rules = [
            'Jibu TU kwa lugha ya ujumbe wa mwisho wa mwananchi (Kiswahili hapa). Akibadili lugha, badilika naye.',
            "Usizidi maneno {$maxWords}. " . ($channel === 'sms'
                ? 'Huu ni ujumbe wa SMS: sentensi fupi, jambo muhimu zaidi kwanza, orodha isizidi vipengele 4.'
                : 'Tumia aya fupi; orodha fupi ya namba inafaa kwa hatua.'),
            'Maandishi ya kawaida tu. Bila markdown, bila nyota (*), bila emoji, bila vichwa vya habari.',
            'Bila salamu, bila "kama msaidizi", bila kurudia swali. Anza na jibu.',
            'Kuwa mkweli: usipojua au taarifa inaweza kuwa imebadilika (ada, viongozi, tarehe za mwisho), sema hivyo na taja ofisi au tovuti inayoweza kuthibitisha.',
            'Usizue kamwe namba za simu, bei, sheria au majina ya viongozi. Tumia tu namba zilizotolewa kwenye maelekezo haya au zinazojulikana wazi.',
            'Usitoe dozi za dawa, usijifanye wakili, usifanye kampeni za kisiasa, usihukumu dini.',
            'Kama mwananchi ameuliza swali la kufuatilia, tumia mazungumzo ya awali kuendeleza mtiririko.',
        ];
        if ($isEmergency) {
            array_unshift($rules, 'DHARURA: mstari wa kwanza lazima uwe namba ya dharura na hatua ya haraka.');
        }
        $out = "KANUNI ZA JIBU:\n- " . implode("\n- ", $rules);
        if ($signature !== '') {
            $out .= "\n- Malizia kwa mstari huu haswa: \"{$signature}\"";
        }

        return $out;
    }

    // ------------------------------------------------------------------ lookups

    private function findLocalResources(?array $location, string $category, ?string $userRegion): Collection
    {
        $region = $location['region'] ?? $userRegion;
        if (! $region) {
            return collect();
        }

        $categoryMap = [
            'legal' => ['legal_aid', 'police'],
            'family' => ['police', 'legal_aid', 'government'],
            'health' => ['health'],
            'agriculture' => ['agriculture'],
            'government' => ['government'],
            'education' => ['education'],
            'finance' => ['finance'],
            'business' => ['finance', 'government'],
            'employment' => ['government', 'legal_aid'],
        ];
        $wanted = $categoryMap[$category] ?? null;

        $query = LocalResource::query()->where('is_active', true)
            ->whereRaw('LOWER(region) = ?', [Str::lower($region)]);
        if ($wanted) {
            $query->whereIn('category', $wanted);
        }

        $all = $query->get();
        if ($all->isEmpty()) {
            return collect();
        }

        $district = $location['district'] ?? null;
        $area = $location['area'] ?? null;

        return $all->sortByDesc(function (LocalResource $r) use ($district, $area) {
            $score = 0;
            if ($district && Str::lower((string) $r->district) === $district) {
                $score += 5;
            }
            if ($area && str_contains(Str::lower((string) $r->location . ' ' . $r->ward), $area)) {
                $score += 8;
            }

            return $score;
        })->take(4)->values();
    }

    private function findContacts(string $category, bool $isEmergency): Collection
    {
        $contacts = Cache::remember('huru.contacts.active', 3600, fn () => ReferenceContact::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get());

        $related = [
            'legal' => ['legal', 'police'],
            'family' => ['family', 'police', 'legal'],
            'health' => ['health'],
            'agriculture' => ['agriculture'],
            'education' => ['education'],
            'government' => ['government'],
            'finance' => ['finance'],
            'business' => ['business', 'finance'],
            'employment' => ['employment', 'legal'],
            'technology' => ['technology'],
            'transport' => ['transport', 'police'],
            'general' => [],
        ];
        $wanted = $related[$category] ?? [];

        $selected = $contacts->filter(fn (ReferenceContact $c) => in_array($c->category, $wanted, true));

        if ($isEmergency) {
            $selected = $contacts->filter(fn (ReferenceContact $c) => $c->is_emergency)->merge($selected);
        }

        return $selected->unique('id')->take(8)->values();
    }

    private function label(string $category, string $lang): string
    {
        return TopicClassifier::label($category, $lang);
    }
}
