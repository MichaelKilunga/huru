<?php

namespace Tests\Unit;

use App\Models\KnowledgeEntry;
use App\Models\LocalResource;
use App\Models\PromptTemplate;
use App\Models\ReferenceContact;
use App\Services\PromptEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromptEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_injects_matching_knowledge_and_country_context(): void
    {
        PromptTemplate::create(['name' => 'Legal SW', 'category' => 'legal', 'language' => 'sw', 'template' => 'Wewe ni {app_name}, mwelimishaji wa sheria.', 'is_active' => true]);
        KnowledgeEntry::create([
            'title' => 'Haki zako ukikamatwa na polisi',
            'content' => 'Polisi wanapaswa kukufikisha mahakamani ndani ya saa 24.',
            'category' => 'legal', 'language' => 'sw', 'keywords' => ['kukamatwa', 'polisi', 'dhamana'], 'is_active' => true,
        ]);

        $built = app(PromptEngine::class)->build([
            'question' => 'Nimekamatwa na polisi, haki zangu ni zipi?',
            'language' => 'sw', 'category' => 'legal', 'channel' => 'sms',
        ]);

        $this->assertStringContainsString('saa 24', $built['system']);
        $this->assertStringContainsString('mwelimishaji wa sheria', $built['system']);
        $this->assertStringContainsString('Tanzania', $built['system']);
        $this->assertStringContainsString('TZS', $built['system']);
        $this->assertSame('user', $built['turns'][0]['role']);
        $this->assertCount(1, $built['knowledge']);
    }

    public function test_it_adds_nearby_services_when_location_is_mentioned(): void
    {
        LocalResource::create(['name' => 'KAHAMA PARALEGAL AID ORGANIZATION', 'category' => 'legal_aid', 'region' => 'shinyanga', 'district' => 'kahama', 'location' => 'Kahama mjini', 'phone' => '+255 767 994457', 'is_active' => true]);

        $built = app(PromptEngine::class)->build([
            'question' => 'naomba msaada wa kisheria nipo kahama shinyanga',
            'language' => 'sw', 'category' => 'legal', 'channel' => 'web',
            'location' => ['region' => 'shinyanga', 'district' => 'kahama', 'area' => null],
        ]);

        $this->assertStringContainsString('KAHAMA PARALEGAL AID ORGANIZATION', $built['system']);
        $this->assertStringContainsString('+255 767 994457', $built['system']);
    }

    public function test_emergency_puts_emergency_contacts_in_prompt_and_history_is_kept(): void
    {
        ReferenceContact::create(['name' => 'Ambulance', 'category' => 'health', 'phone' => '115', 'is_emergency' => true, 'is_active' => true]);

        $built = app(PromptEngine::class)->build([
            'question' => 'My father collapsed and is not breathing',
            'language' => 'en', 'category' => 'health', 'channel' => 'web', 'is_emergency' => true,
            'history' => [['role' => 'user', 'text' => 'hello'], ['role' => 'model', 'text' => 'hi']],
        ]);

        $this->assertStringContainsString('115', $built['system']);
        $this->assertStringContainsString('EMERGENCY', $built['system']);
        $this->assertCount(3, $built['turns']);
    }
}
