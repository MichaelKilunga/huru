<?php

namespace App\Services;

use App\Services\Ai\LlmClient;
use App\Support\Settings;

/**
 * Thin orchestration between the prompt engine and the model client.
 */
class AiService
{
    public function __construct(
        private readonly PromptEngine $engine,
        private readonly LlmClient $client,
    ) {
    }

    /**
     * @param  array  $ctx  See PromptEngine::build()
     * @return array{status:string,text:?string,model:string,tokens:array,latency_ms:int,error:?string,system:string,knowledge:\Illuminate\Support\Collection}
     */
    public function answer(array $ctx): array
    {
        $built = $this->engine->build($ctx);

        $result = $this->client->generate($built['system'], $built['turns'], [
            'model' => Settings::get('ai_model'),
            'temperature' => Settings::float('ai_temperature'),
            'max_tokens' => Settings::int('ai_max_tokens'),
        ]);

        if ($result['status'] === LlmClient::STATUS_OK) {
            $result['text'] = $this->clean($result['text']);
        }

        $result['system'] = $built['system'];
        $result['knowledge'] = $built['knowledge'];

        return $result;
    }

    /** Strip markdown the model may still emit, since SMS cannot render it. */
    private function clean(string $text): string
    {
        $text = preg_replace('/\*\*?/', '', $text);
        $text = preg_replace('/^#{1,6}\s*/m', '', $text);
        $text = preg_replace('/`+/', '', $text);
        $text = preg_replace('/^\s*[-•]\s+/m', '- ', $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    }

    public function isConfigured(): bool
    {
        return $this->client->isConfigured();
    }
}
