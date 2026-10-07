<?php

namespace Tests\Feature;

use App\Jobs\ProcessIncomingSms;
use App\Models\AiLog;
use App\Models\Message;
use App\Models\User;
use App\Services\Ai\LlmClient;
use App\Services\SmsService;
use App\Support\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

class SmsFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_inbound_webhook_rejects_bad_token_when_secret_is_set(): void
    {
        config(['services.at.webhook_secret' => 'topsecret']);
        Queue::fake();

        $this->post('/api/sms/inbound', ['from' => '+255712345678', 'text' => 'HURU habari'])->assertStatus(401);
        $this->post('/api/sms/inbound?token=topsecret', ['from' => '+255712345678', 'text' => 'HURU habari'])->assertOk();

        Queue::assertPushed(ProcessIncomingSms::class, fn ($job) => $job->from === '+255712345678' && $job->text === 'habari');
    }

    public function test_inbound_strips_keyword_and_normalises_phone(): void
    {
        config(['services.at.webhook_secret' => null]);
        Queue::fake();

        $this->post('/api/sms/inbound', ['from' => '0712345678', 'text' => 'huru  Nifanyeje kupata NIDA?'])->assertOk();

        Queue::assertPushed(ProcessIncomingSms::class, fn ($job) => $job->from === '+255712345678' && $job->text === 'Nifanyeje kupata NIDA?');
    }

    public function test_job_answers_logs_and_sends_sms(): void
    {
        $this->mock(LlmClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('generate')->once()->andReturn([
                'status' => LlmClient::STATUS_OK, 'text' => 'Nenda ofisi ya mtendaji wa kata na cheti cha kuzaliwa.',
                'model' => 'gemini-test', 'tokens' => ['prompt' => 10, 'completion' => 5, 'total' => 15], 'latency_ms' => 120, 'finish_reason' => 'STOP', 'error' => null,
            ]);
        });
        $this->mock(SmsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once()->with('+255712345678', 'Nenda ofisi ya mtendaji wa kata na cheti cha kuzaliwa.')->andReturn(['status' => 'success']);
        });

        (new ProcessIncomingSms('+255712345678', 'Nifanyeje kupata kitambulisho cha NIDA?'))->handle(app(\App\Services\ConversationService::class), app(SmsService::class));

        $this->assertDatabaseHas('users', ['phone_number' => '+255712345678']);
        $this->assertDatabaseHas('messages', ['direction' => 'inbound', 'channel' => 'sms', 'category' => 'government']);
        $this->assertDatabaseHas('messages', ['direction' => 'outbound', 'content' => 'Nenda ofisi ya mtendaji wa kata na cheti cha kuzaliwa.']);
        $log = AiLog::first();
        $this->assertSame('gemini-test', $log->model);
        $this->assertSame(15, $log->total_tokens);
        $this->assertSame('government', $log->category);
    }

    public function test_help_and_language_commands_do_not_call_the_model(): void
    {
        $this->mock(LlmClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('generate')->never();
        });
        $this->mock(SmsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->twice()->andReturn(['status' => 'success']);
        });

        (new ProcessIncomingSms('+255712345678', 'MSAADA'))->handle(app(\App\Services\ConversationService::class), app(SmsService::class));
        (new ProcessIncomingSms('+255712345678', 'LUGHA EN'))->handle(app(\App\Services\ConversationService::class), app(SmsService::class));

        $user = User::where('phone_number', '+255712345678')->first();
        $this->assertSame('en', $user->preferred_language);
        $this->assertSame(4, Message::count());
    }

    public function test_abuse_leads_to_warning_then_ban(): void
    {
        Settings::set('abuse_strike_limit', 2);
        $this->mock(LlmClient::class, fn (MockInterface $m) => $m->shouldReceive('generate')->never());
        $this->mock(SmsService::class, fn (MockInterface $m) => $m->shouldReceive('send')->twice()->andReturn(['status' => 'success']));

        $svc = app(\App\Services\ConversationService::class);
        (new ProcessIncomingSms('+255712345678', 'wewe mjinga'))->handle($svc, app(SmsService::class));
        (new ProcessIncomingSms('+255712345678', 'mjinga tena'))->handle($svc, app(SmsService::class));

        $this->assertTrue(User::where('phone_number', '+255712345678')->first()->is_banned);
    }

    public function test_opted_out_user_is_ignored_until_start(): void
    {
        $this->mock(LlmClient::class, fn (MockInterface $m) => $m->shouldReceive('generate')->never());
        $this->mock(SmsService::class, fn (MockInterface $m) => $m->shouldReceive('send')->twice()->andReturn(['status' => 'success']));

        $svc = app(\App\Services\ConversationService::class);
        (new ProcessIncomingSms('+255712345678', 'ACHA'))->handle($svc, app(SmsService::class));
        (new ProcessIncomingSms('+255712345678', 'swali lolote'))->handle($svc, app(SmsService::class)); // no reply
        (new ProcessIncomingSms('+255712345678', 'ANZA'))->handle($svc, app(SmsService::class));

        $this->assertFalse(User::first()->opted_out);
    }
}
