<?php

namespace Tests\Unit;

use App\Services\TopicClassifier;
use PHPUnit\Framework\TestCase;

class TopicClassifierTest extends TestCase
{
    private TopicClassifier $classifier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classifier = new TopicClassifier();
    }

    public function test_detects_legal_questions_in_swahili(): void
    {
        $r = $this->classifier->classify('Nimekamatwa na polisi bila sababu, naomba msaada wa kisheria');
        $this->assertSame('legal', $r['category']);
        $this->assertFalse($r['is_emergency']);
    }

    public function test_detects_health_questions_in_english(): void
    {
        $r = $this->classifier->classify('My child has fever and vomiting, is it malaria?');
        $this->assertSame('health', $r['category']);
    }

    public function test_detects_agriculture(): void
    {
        $r = $this->classifier->classify('Nipande mahindi lini na nitumie mbolea gani?');
        $this->assertSame('agriculture', $r['category']);
    }

    public function test_detects_government_services(): void
    {
        $r = $this->classifier->classify('How do I get a NIDA national id and a passport?');
        $this->assertSame('government', $r['category']);
    }

    public function test_falls_back_to_general(): void
    {
        $r = $this->classifier->classify('Habari za asubuhi');
        $this->assertSame('general', $r['category']);
    }

    public function test_flags_emergencies(): void
    {
        $r = $this->classifier->classify('Dharura! mtoto amezimia na hapumui');
        $this->assertTrue($r['is_emergency']);
        $this->assertSame('health', $r['category']);
    }

    public function test_labels_exist_for_every_category(): void
    {
        foreach (TopicClassifier::keys() as $key) {
            $this->assertNotEmpty(TopicClassifier::label($key, 'sw'));
            $this->assertNotEmpty(TopicClassifier::label($key, 'en'));
        }
    }
}
