<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\PromptEngine;

class AiService
{
    public function generateResponse(string $prompt): string
    {
        $result = $this->callGemini($prompt);
        return $result['text'];
    }

    public function generateContextualResponse(string $userQuery, string $language = 'sw'): array
    {
        $engine = new PromptEngine();
        $prompt = $engine->build($userQuery, $language);

        return $this->callGemini($prompt);
    }

    private function callGemini(string $prompt, ?float $temp = null, ?int $maxTokens = null): array
    {
        try {
            $temp = $temp ?? (float) (\App\Models\SystemSetting::where('key', 'ai_temperature')->value('value') ?? 0.7);
            $maxTokens = $maxTokens ?? (int) (\App\Models\SystemSetting::where('key', 'ai_max_tokens')->value('value') ?? 8000);

            $apiKey = config('services.gemini.key');

            if (empty($apiKey)) {
                Log::critical('Gemini API Key missing.');
                return ['text' => 'System error: AI unavailable.', 'tokens' => null];
            }

            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key=' . $apiKey;

            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'X-Goog-Api-Key' => $apiKey,
            ])->retry(3, 1000)->post($url, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => [
                    'temperature' => $temp,
                    'maxOutputTokens' => $maxTokens,
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Gemini request failed: ' . $response->status());
            }

            $data = $response->json();
            
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            $finishReason = $data['candidates'][0]['finishReason'] ?? null;

            if (!$text && $finishReason === 'SAFETY') {
                return [
                    'text' => 'BANNED_CONTENT_DETECTED',
                    'tokens' => $data['usageMetadata'] ?? null,
                    'model' => 'gemini-flash-lite-latest'
                ];
            }

            return [
                'text' => trim($text ?? 'Samahani, sikuelewa.'),
                'tokens' => $data['usageMetadata'] ?? null,
                'model' => 'gemini-flash-lite-latest'
            ];

        } catch (\Throwable $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            return [
                'text' => 'Samahani, tafadhali jaribu tena baada ya muda mfupi.',
                'tokens' => null
            ];
        }
    }
}
