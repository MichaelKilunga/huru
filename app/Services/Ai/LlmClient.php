<?php

namespace App\Services\Ai;

interface LlmClient
{
    public const STATUS_OK = 'ok';
    public const STATUS_SAFETY = 'safety';
    public const STATUS_ERROR = 'error';
    public const STATUS_UNCONFIGURED = 'unconfigured';

    /**
     * @param  array<int, array{role:string, text:string}>  $turns  Ordered conversation, last item is the new user message.
     * @param  array{temperature?:float, max_tokens?:int, model?:string}  $options
     * @return array{status:string, text:?string, model:string, tokens:array{prompt:int,completion:int,total:int}, latency_ms:int, finish_reason:?string, error:?string}
     */
    public function generate(string $systemInstruction, array $turns, array $options = []): array;

    public function isConfigured(): bool;
}
