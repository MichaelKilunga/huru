<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Google Gemini generateContent client.
 *
 * - Sends the API key in a header, never in the URL (keeps it out of logs).
 * - Keeps TLS verification on.
 * - Uses a real system instruction and multi-turn contents.
 * - Normalises the response into a provider-agnostic shape.
 */
class GeminiClient implements LlmClient
{
    public function __construct(
        private readonly ?string $apiKey,
        private readonly string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models',
        private readonly int $timeoutSeconds = 30,
    ) {
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    public function generate(string $systemInstruction, array $turns, array $options = []): array
    {
        $model = $options['model'] ?? 'gemini-flash-lite-latest';
        $base = [
            'status' => self::STATUS_ERROR,
            'text' => null,
            'model' => $model,
            'tokens' => ['prompt' => 0, 'completion' => 0, 'total' => 0],
            'latency_ms' => 0,
            'finish_reason' => null,
            'error' => null,
        ];

        if (! $this->isConfigured()) {
            Log::critical('Gemini API key missing.');

            return array_merge($base, ['status' => self::STATUS_UNCONFIGURED, 'error' => 'missing_api_key']);
        }

        $contents = [];
        foreach ($turns as $turn) {
            $contents[] = [
                'role' => $turn['role'] === 'model' ? 'model' : 'user',
                'parts' => [['text' => (string) $turn['text']]],
            ];
        }

        $payload = [
            'systemInstruction' => ['parts' => [['text' => $systemInstruction]]],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => (float) ($options['temperature'] ?? 0.4),
                'maxOutputTokens' => (int) ($options['max_tokens'] ?? 2048),
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_ONLY_HIGH'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
            ],
        ];

        $started = microtime(true);

        try {
            $response = Http::timeout($this->timeoutSeconds)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey,
                ])
                ->retry(2, 800, throw: false)
                ->post(rtrim($this->baseUrl, '/') . '/' . rawurlencode($model) . ':generateContent', $payload);

            $latency = (int) round((microtime(true) - $started) * 1000);
            $base['latency_ms'] = $latency;

            if (! $response->successful()) {
                $body = $response->json();
                $message = $body['error']['message'] ?? ('HTTP ' . $response->status());
                Log::error('Gemini request failed', ['status' => $response->status(), 'message' => $message]);

                return array_merge($base, ['error' => $message]);
            }

            $data = $response->json();
            $usage = $data['usageMetadata'] ?? [];
            $base['tokens'] = [
                'prompt' => (int) ($usage['promptTokenCount'] ?? 0),
                'completion' => (int) ($usage['candidatesTokenCount'] ?? 0),
                'total' => (int) ($usage['totalTokenCount'] ?? 0),
            ];

            $candidate = $data['candidates'][0] ?? null;
            $finish = $candidate['finishReason'] ?? null;
            $base['finish_reason'] = $finish;

            $blockReason = $data['promptFeedback']['blockReason'] ?? null;
            if ($blockReason || $finish === 'SAFETY' || $finish === 'PROHIBITED_CONTENT') {
                return array_merge($base, ['status' => self::STATUS_SAFETY]);
            }

            $text = '';
            foreach ($candidate['content']['parts'] ?? [] as $part) {
                $text .= $part['text'] ?? '';
            }
            $text = trim($text);

            if ($text === '') {
                return array_merge($base, ['error' => 'empty_response']);
            }

            return array_merge($base, ['status' => self::STATUS_OK, 'text' => $text]);
        } catch (\Throwable $e) {
            Log::error('Gemini client exception: ' . $e->getMessage());

            return array_merge($base, [
                'latency_ms' => (int) round((microtime(true) - $started) * 1000),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
