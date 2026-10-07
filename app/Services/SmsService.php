<?php

namespace App\Services;

class SmsService
{
    protected $username;
    protected $apiKey;
    protected $from;

    public function __construct()
    {
        $this->username = config('services.at.username');
        $this->apiKey = config('services.at.api_key');
        $this->from = config('services.at.from');
    }

    public function send($to, $message)
    {
        \Illuminate\Support\Facades\Log::info("SMS attempt to: $to | Username: {$this->username} | Key Prefix: " . substr($this->apiKey, 0, 8));

        // Initialize the SDK
        $AT = new \AfricasTalking\SDK\AfricasTalking($this->username, $this->apiKey);

        // Get the SMS service
        $sms = $AT->sms();

        try {
            // That's it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $to,
                'message' => $message,
                'from'    => $this->from
            ]);

            return [
                'status' => 'success',
                'data' => $result
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("SMS Send Error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
