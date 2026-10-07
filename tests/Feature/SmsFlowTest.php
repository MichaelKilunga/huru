<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Jobs\ProcessIncomingSms;
use App\Services\SmsService;
use App\Services\AiService;
use Mockery\MockInterface;
use Illuminate\Support\Facades\Queue;
use App\Models\User;
use App\Models\Message;
use App\Models\AiLog;
use App\Services\ModerationService;

class SmsFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_inbound_sms_dispatches_job()
    {
        Queue::fake();

        $response = $this->postJson('/api/sms/inbound', [
            'from' => '+254700000000',
            'text' => 'HURU What is photosynthesis?',
        ]);

        $response->assertStatus(200);
        
        Queue::assertPushed(ProcessIncomingSms::class, function ($job) {
            // Need to expose protected properties or use reflection to test properties if needed
            // For now just checking class is pushed
            return true;
        });
    }

    public function test_process_sms_job_logic()
    {
        // Mock AiService
        $this->mock(AiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateContextualResponse')
                 ->once()
                 ->andReturn(['text' => 'Photosynthesis is how plants make food.', 'model' => 'gemini-2.5-flash', 'tokens' => null]);
        });

        // Mock SmsService
        $this->mock(SmsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')
                 ->once()
                 ->with('+254700000000', 'Photosynthesis is how plants make food.')
                 ->andReturn(['status' => 'success']);
        });

        // Mock ModerationService
        $this->mock(ModerationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('isAbusive')
                 ->once()
                 ->andReturn(false);
        });

        // Create the job instance manually
        $job = new ProcessIncomingSms('+254700000000', 'What is photosynthesis?');
        
        // Resolve mocks
        $aiService = app(AiService::class);
        $smsService = app(SmsService::class);
        $moderationService = app(ModerationService::class);

        // Run the handle method
        $job->handle($aiService, $smsService, $moderationService);

        // Assert Database State
        $this->assertDatabaseHas('users', ['phone_number' => '+254700000000']);
        
        $this->assertDatabaseHas('messages', [
            'direction' => 'inbound',
            'content' => 'What is photosynthesis?',
        ]);

        $this->assertDatabaseHas('messages', [
            'direction' => 'outbound',
            'content' => 'Photosynthesis is how plants make food.',
        ]);

        $aiLog = AiLog::first();
        $this->assertEquals('What is photosynthesis?', $aiLog->prompt);
        $this->assertEquals('Photosynthesis is how plants make food.', $aiLog->response);
    }
}
