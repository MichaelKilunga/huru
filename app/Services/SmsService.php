<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected ?string $username;
    protected ?string $apiKey;
    protected ?string $from;

    public function __construct()
    {
        $this->username = config('services.at.username');
        $this->apiKey = config('services.at.api_key');
        $this->from = config('services.at.from');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->username) && ! empty($this->apiKey);
    }

    /**
     * @return array{status:string, data?:mixed, message?:string}
     */
    public function send(string $to, string $message): array
    {
        if (! $this->isConfigured()) {
            Log::warning('SMS not sent: Africa\'s Talking credentials missing.', ['to' => $this->mask($to)]);

            return ['status' => 'skipped', 'message' => 'sms_not_configured'];
        }

        Log::info('SMS send', ['to' => $this->mask($to), 'chars' => strlen($message)]);

        try {
            $at = new AfricasTalking($this->username, $this->apiKey);
            $payload = ['to' => $to, 'message' => $message];
            if ($this->from) {
                $payload['from'] = $this->from;
            }
            $result = $at->sms()->send($payload);

            return ['status' => 'success', 'data' => $result];
        } catch (\Throwable $e) {
            Log::error('SMS send error: ' . $e->getMessage());

            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function sendOtp(string $to, string $code, string $language, string $appName, int $ttlMinutes): array
    {
        $text = $language === 'en'
            ? "{$appName}: your verification code is {$code}. It expires in {$ttlMinutes} minutes. Do not share it with anyone."
            : "{$appName}: namba yako ya uthibitisho ni {$code}. Inaisha baada ya dakika {$ttlMinutes}. Usimpe mtu yeyote.";

        return $this->send($to, $text);
    }

    private function mask(string $phone): string
    {
        return strlen($phone) > 6 ? substr($phone, 0, 4) . '****' . substr($phone, -3) : '****';
    }
}
