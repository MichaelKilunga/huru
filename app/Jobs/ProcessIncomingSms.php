<?php

namespace App\Jobs;

use App\Models\Message;
use App\Models\User;
use App\Services\ConversationService;
use App\Services\SmsService;
use App\Support\Phone;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessIncomingSms implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 2;

    public int $timeout = 90;

    public function __construct(
        public readonly string $from,
        public readonly string $text,
        public readonly ?string $providerMessageId = null,
    ) {
    }

    public function handle(ConversationService $conversation, SmsService $sms): void
    {
        $user = User::query()->firstOrCreate(['phone_number' => $this->from]);

        $result = $conversation->handle($user, $this->text, Message::CHANNEL_SMS);

        if (empty($result['text'])) {
            Log::info('No SMS reply', ['from' => Phone::mask($this->from), 'kind' => $result['kind']]);

            return;
        }

        $send = $sms->send($this->from, $result['text']);

        Log::info('SMS reply', [
            'from' => Phone::mask($this->from),
            'kind' => $result['kind'],
            'category' => $result['category'],
            'delivery' => $send['status'],
        ]);
    }

    /** Use the job's own key so retries do not duplicate replies. */
    public function uniqueId(): string
    {
        return $this->providerMessageId ?: md5($this->from . '|' . $this->text);
    }
}
