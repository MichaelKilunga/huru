<?php

namespace App\Support;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

/**
 * Typed, cached, whitelisted access to system_settings.
 *
 * Only keys declared in schema() can be stored. The admin settings form,
 * the validation rules and the defaults are all driven by this schema so
 * there is a single source of truth.
 */
class Settings
{
    private const CACHE_KEY = 'huru.settings.all';

    /**
     * @return array<string, array{group:string,label:string,type:string,default:mixed,rules:string,hint?:string,options?:array}>
     */
    public static function schema(): array
    {
        return [
            // ---- Branding ------------------------------------------------
            'app_name' => [
                'group' => 'Branding', 'label' => 'Service name', 'type' => 'text',
                'default' => 'Huru SMS', 'rules' => 'required|string|max:40',
                'hint' => 'Shown in replies, the web chat and SMS help text.',
            ],
            'sms_keyword' => [
                'group' => 'Branding', 'label' => 'SMS keyword', 'type' => 'text',
                'default' => 'HURU', 'rules' => 'required|alpha|max:15',
                'hint' => 'Word citizens send first. Must match the Africa\'s Talking keyword.',
            ],
            'sms_shortcode' => [
                'group' => 'Branding', 'label' => 'SMS shortcode', 'type' => 'text',
                'default' => '15054', 'rules' => 'required|string|max:10',
            ],
            'signature_sw' => [
                'group' => 'Branding', 'label' => 'Reply sign-off (Swahili)', 'type' => 'text',
                'default' => '', 'rules' => 'nullable|string|max:120',
                'hint' => 'Optional short line appended to every reply. Leave empty for none.',
            ],
            'signature_en' => [
                'group' => 'Branding', 'label' => 'Reply sign-off (English)', 'type' => 'text',
                'default' => '', 'rules' => 'nullable|string|max:120',
            ],

            // ---- Engine --------------------------------------------------
            'ai_enabled' => [
                'group' => 'Engine', 'label' => 'Engine enabled', 'type' => 'boolean',
                'default' => '1', 'rules' => 'required|in:0,1',
                'hint' => 'Turn off to pause replies during maintenance or when budget is exhausted.',
            ],
            'ai_maintenance_message_sw' => [
                'group' => 'Engine', 'label' => 'Maintenance message (Swahili)', 'type' => 'textarea',
                'default' => 'Huduma inafanyiwa matengenezo kwa muda mfupi. Tafadhali jaribu tena baadaye.',
                'rules' => 'required|string|max:300',
            ],
            'ai_maintenance_message_en' => [
                'group' => 'Engine', 'label' => 'Maintenance message (English)', 'type' => 'textarea',
                'default' => 'The service is under brief maintenance. Please try again later.',
                'rules' => 'required|string|max:300',
            ],
            'ai_model' => [
                'group' => 'Engine', 'label' => 'Gemini model', 'type' => 'text',
                'default' => 'gemini-flash-lite-latest', 'rules' => 'required|string|max:80',
                'hint' => 'Any generateContent-capable Gemini model id.',
            ],
            'ai_temperature' => [
                'group' => 'Engine', 'label' => 'Temperature', 'type' => 'number',
                'default' => '0.4', 'rules' => 'required|numeric|min:0|max:2',
                'hint' => 'Lower is more factual. 0.3 to 0.5 is recommended for public guidance.',
            ],
            'ai_max_tokens' => [
                'group' => 'Engine', 'label' => 'Max output tokens', 'type' => 'number',
                'default' => '2048', 'rules' => 'required|integer|min:64|max:8192',
            ],
            'history_turns' => [
                'group' => 'Engine', 'label' => 'Conversation memory (turns)', 'type' => 'number',
                'default' => '4', 'rules' => 'required|integer|min:0|max:12',
                'hint' => 'How many previous question/answer pairs are given to the engine for follow-up questions.',
            ],
            'knowledge_matches' => [
                'group' => 'Engine', 'label' => 'Knowledge entries per reply', 'type' => 'number',
                'default' => '2', 'rules' => 'required|integer|min:0|max:5',
            ],

            // ---- Channels ------------------------------------------------
            'primary_language' => [
                'group' => 'Channels', 'label' => 'Language policy', 'type' => 'select',
                'default' => 'auto', 'rules' => 'required|in:auto,sw,en',
                'options' => ['auto' => 'Detect automatically', 'sw' => 'Always Swahili', 'en' => 'Always English'],
            ],
            'sms_max_words' => [
                'group' => 'Channels', 'label' => 'SMS reply length (words)', 'type' => 'number',
                'default' => '120', 'rules' => 'required|integer|min:30|max:400',
                'hint' => 'Roughly 160 characters per SMS segment. 120 words is about 5 segments.',
            ],
            'web_max_words' => [
                'group' => 'Channels', 'label' => 'Web reply length (words)', 'type' => 'number',
                'default' => '250', 'rules' => 'required|integer|min:50|max:800',
            ],
            'web_chat_limit' => [
                'group' => 'Channels', 'label' => 'Web history shown', 'type' => 'number',
                'default' => '20', 'rules' => 'required|integer|min:2|max:200',
            ],
            'otp_enabled' => [
                'group' => 'Channels', 'label' => 'Web login verification (OTP)', 'type' => 'boolean',
                'default' => '1', 'rules' => 'required|in:0,1',
                'hint' => 'Sends a one-time code by SMS before a phone number can open its chat history.',
            ],
            'otp_ttl_minutes' => [
                'group' => 'Channels', 'label' => 'OTP validity (minutes)', 'type' => 'number',
                'default' => '5', 'rules' => 'required|integer|min:1|max:30',
            ],

            // ---- Safety --------------------------------------------------
            'abuse_strike_limit' => [
                'group' => 'Safety', 'label' => 'Strikes before ban', 'type' => 'number',
                'default' => '3', 'rules' => 'required|integer|min:1|max:10',
            ],
            'rate_limit_per_minute' => [
                'group' => 'Safety', 'label' => 'Questions per minute per user', 'type' => 'number',
                'default' => '6', 'rules' => 'required|integer|min:1|max:60',
            ],
            'contact_email' => [
                'group' => 'Safety', 'label' => 'Operator contact email', 'type' => 'text',
                'default' => '', 'rules' => 'nullable|email|max:120',
                'hint' => 'Receives landing page enquiries.',
            ],
        ];
    }

    public static function groups(): array
    {
        $groups = [];
        foreach (self::schema() as $key => $def) {
            $groups[$def['group']][$key] = $def;
        }

        return $groups;
    }

    public static function isKnown(string $key): bool
    {
        return array_key_exists($key, self::schema());
    }

    /** @return array<string,string|null> */
    public static function all(): array
    {
        $stored = Cache::remember(self::CACHE_KEY, 3600, function () {
            try {
                return SystemSetting::query()->pluck('value', 'key')->all();
            } catch (\Throwable) {
                return [];
            }
        });

        $out = [];
        foreach (self::schema() as $key => $def) {
            $out[$key] = array_key_exists($key, $stored) && $stored[$key] !== null
                ? $stored[$key]
                : $def['default'];
        }

        return $out;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::all();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        return $default ?? (self::schema()[$key]['default'] ?? null);
    }

    public static function bool(string $key): bool
    {
        return in_array((string) self::get($key), ['1', 'true', 'on', 'yes'], true);
    }

    public static function int(string $key): int
    {
        return (int) self::get($key);
    }

    public static function float(string $key): float
    {
        return (float) self::get($key);
    }

    public static function set(string $key, mixed $value): void
    {
        if (! self::isKnown($key)) {
            throw new \InvalidArgumentException("Unknown setting [{$key}]");
        }

        SystemSetting::query()->updateOrCreate(['key' => $key], ['value' => $value === null ? null : (string) $value]);
        self::flush();
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /** Validation rules for the whole schema, used by the admin form. */
    public static function rules(): array
    {
        $rules = [];
        foreach (self::schema() as $key => $def) {
            $rules[$key] = $def['rules'];
        }

        return $rules;
    }
}
