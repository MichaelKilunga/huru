<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Ai\LlmClient;
use App\Services\SmsService;
use App\Support\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery\MockInterface;
use Tests\TestCase;

class WebChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_without_otp_when_sms_is_not_configured_locally(): void
    {
        $this->mock(SmsService::class, fn (MockInterface $m) => $m->shouldReceive('isConfigured')->andReturn(false));

        $login = $this->postJson('/chat/request-otp', ['phone_number' => '0712345678'])
            ->assertOk()
            ->assertJsonPath('otp_required', false)
            ->assertJsonPath('user.phone_masked', '+255 *** 678');

        // Login regenerates the session; the rotated CSRF token must be handed back.
        $this->assertNotEmpty($login->json('csrf'));
        $this->assertSame($login->json('csrf'), $this->getJson('/chat/session')->assertJsonPath('authenticated', true)->json('csrf'));
    }

    public function test_otp_flow_when_sms_is_configured(): void
    {
        Settings::set('otp_enabled', '1');
        $this->mock(SmsService::class, function (MockInterface $m) {
            $m->shouldReceive('isConfigured')->andReturn(true);
            $m->shouldReceive('sendOtp')->once()->andReturn(['status' => 'success']);
        });

        $r = $this->postJson('/chat/request-otp', ['phone_number' => '0712345678'])->assertOk()->assertJsonPath('otp_required', true);
        $code = $r->json('debug_code');
        $this->assertNotEmpty($code, 'debug code is exposed in testing');

        $this->getJson('/chat/session')->assertJsonPath('authenticated', false);
        $this->postJson('/chat/verify-otp', ['phone_number' => '0712345678', 'code' => '000000'])->assertStatus(422);
        $this->postJson('/chat/verify-otp', ['phone_number' => '0712345678', 'code' => $code])->assertOk();
        $this->getJson('/chat/session')->assertJsonPath('authenticated', true);

        $this->assertNull(User::first()->otp_hash);
    }

    public function test_messages_require_a_session_and_send_returns_reply(): void
    {
        $this->getJson('/chat/messages')->assertStatus(401);

        $this->mock(LlmClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('generate')->once()->andReturn([
                'status' => LlmClient::STATUS_OK, 'text' => 'Malaria danger signs include convulsions.',
                'model' => 'gemini-test', 'tokens' => ['prompt' => 1, 'completion' => 1, 'total' => 2], 'latency_ms' => 50, 'finish_reason' => 'STOP', 'error' => null,
            ]);
        });

        $user = User::factory()->create(['phone_number' => '+255712345678']);

        $this->withSession(['chat_user_id' => $user->id])
            ->postJson('/chat/send', ['message' => 'What are malaria danger signs in a child?'])
            ->assertOk()
            ->assertJsonPath('kind', 'answer')
            ->assertJsonPath('category', 'health')
            ->assertJsonPath('reply.content', 'Malaria danger signs include convulsions.');

        $replyId = $this->withSession(['chat_user_id' => $user->id])->getJson('/chat/messages')->json('messages.1.id');

        $this->withSession(['chat_user_id' => $user->id])
            ->postJson('/chat/feedback', ['message_id' => $replyId, 'rating' => 1])
            ->assertOk();

        $this->assertDatabaseHas('feedback', ['message_id' => $replyId, 'rating' => 1]);
    }

    public function test_admin_area_requires_an_admin_account(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');

        $citizen = User::factory()->create(['email' => 'c@example.com', 'password' => Hash::make('secret123')]);
        $this->actingAs($citizen)->get('/admin')->assertStatus(403);

        $admin = User::factory()->admin()->create(['email' => 'a@example.com', 'password' => Hash::make('secret123')]);
        $this->post('/admin/login', ['email' => 'a@example.com', 'password' => 'secret123'])->assertRedirect('/admin');
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Dashboard');
        $this->actingAs($admin)->get('/admin/settings')->assertOk()->assertSee('Gemini model');
    }

    public function test_settings_only_accept_known_keys(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/settings', ['app_name' => 'Huru', 'bogus_key' => 'x'] + $this->defaults())->assertRedirect();
        $this->assertDatabaseMissing('system_settings', ['key' => 'bogus_key']);
        $this->assertSame('Huru', Settings::get('app_name'));
    }

    private function defaults(): array
    {
        $out = [];
        foreach (Settings::schema() as $k => $def) {
            $out[$k] = $def['default'];
        }

        return $out;
    }
}
