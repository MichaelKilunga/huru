<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Africa's Talking SMS over their REST API, through Laravel's HTTP client
 * so the global TLS settings, retries and timeouts apply.
 */
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

    public function endpoint(): string
    {
        return $this->username === 'sandbox'
            ? 'https://api.sandbox.africastalking.com/version1/messaging'
            : 'https://api.africastalking.com/version1/messaging';
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

        $payload = ['username' => $this->username, 'to' => $to, 'message' => $message];
        if ($this->from) {
            $payload['from'] = $this->from;
        }

        try {
            $response = Http::timeout(20)
                ->retry(2, 500, throw: false)
                ->withHeaders(['apiKey' => $this->apiKey, 'Accept' => 'application/json'])
                ->asForm()
                ->post($this->endpoint(), $payload);

            $data = $response->json();
            $recipient = $data['SMSMessageData']['Recipients'][0] ?? null;
            $status = $recipient['status'] ?? ($data['SMSMessageData']['Message'] ?? 'HTTP ' . $response->status());

            if (! $response->successful() || ! $recipient || ! in_array($recipient['statusCode'] ?? 0, [100, 101, 102], true)) {
                Log::error('SMS send failed', ['to' => $this->mask($to), 'status' => $status, 'http' => $response->status()]);

                return ['status' => 'error', 'message' => (string) $status, 'data' => $data];
            }

            Log::info('SMS sent', ['to' => $this->mask($to), 'chars' => strlen($message), 'cost' => $recipient['cost'] ?? null]);

            return ['status' => 'success', 'data' => $data];
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
