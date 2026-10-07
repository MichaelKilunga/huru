<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PromptEngine;
use App\Models\PromptTemplate;
use App\Models\Curriculum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromptEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_prompt_engine_injects_legal_disclaimers_and_rules()
    {
        // 1. Create a dummy prompt template
        PromptTemplate::create([
            'name' => 'Default Swahili',
            'language' => 'sw',
            'template' => 'Wewe ni msaidizi wa kisheria nchini Tanzania. Mwongozo: {context}. Swali: {user_input}',
            'is_active' => true,
        ]);

        // 2. Create curriculum entry for context lookup
        Curriculum::create([
            'title' => 'Haki ya Kukamatwa',
            'content' => 'Kila mwananchi ana haki ya kupewa sababu za kukamatwa kwake.',
            'summary' => 'Haki ya kukamatwa',
            'tags' => 'arrest, haki za msingi',
            'keywords' => ['kukamatwa', 'haki'],
            'language' => 'sw',
            'is_active' => true,
        ]);

        $promptEngine = new PromptEngine();
        $prompt = $promptEngine->build('Naomba kujua haki yangu wakati wa kukamatwa', 'sw');

        // Assert curriculum context injected
        $this->assertStringContainsString('Kila mwananchi ana haki ya kupewa sababu', $prompt);
        $this->assertStringContainsString('tanzania', strtolower($prompt));
    }

    public function test_prompt_engine_retrieves_localized_context_for_location_queries()
    {
        // 1. Create a dummy prompt template
        PromptTemplate::create([
            'name' => 'Default Swahili',
            'language' => 'sw',
            'template' => 'Wewe ni msaidizi wa kisheria nchini Tanzania. Mwongozo: {context}. Swali: {user_input}',
            'is_active' => true,
        ]);

        // 2. Create a local provider
        \App\Models\LegalAidProvider::create([
            'name' => 'KAHAMA PARALEGAL AID ORGANIZATION (KAPAO)',
            'region' => 'SHINYANGA',
            'district' => 'KAHAMA',
            'location' => 'Wilaya ya Kahama, Shinyanga.',
            'phone' => '+255 767 994457',
            'language' => 'sw',
        ]);

        $promptEngine = new PromptEngine();
        
        // Query from Kahama
        $prompt = $promptEngine->build('naomba msaada wa kisheria nipo kahama shinyanga', 'sw');

        // Assert that the local provider is dynamically injected into the context!
        $this->assertStringContainsString('KAHAMA PARALEGAL AID ORGANIZATION', $prompt);
        $this->assertStringContainsString('SHINYANGA', $prompt);
        $this->assertStringContainsString('KAHAMA', $prompt);
        $this->assertStringContainsString('+255 767 994457', $prompt);
    }
}
