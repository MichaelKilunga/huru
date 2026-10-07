<?php

namespace App\Services;

use App\Models\Curriculum;
use App\Models\PromptTemplate;
use App\Models\SystemSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ContextService
{
    public function getContextualData(string $userInput)
    {
        $language = SystemSetting::where('key', 'primary_language')->value('value') ?? 'sw';
        
        // 1. Extract potential keywords (simplified)
        $keywords = $this->extractKeywords($userInput);
        
        // 2. Find relevant curriculum
        $context = $this->findRelevantContent($keywords, $language);
        
        // 3. Get Template
        $template = PromptTemplate::where('is_active', true)
            ->where('language', $language)
            ->first();

        // Fallback to any active template if none for specific language
        if (!$template) {
            $template = PromptTemplate::where('is_active', true)->first();
        }

        return [
            'context' => $context,
            'template' => $template,
            'language' => $language
        ];
    }

    protected function extractKeywords(string $input): array
    {
        // Remove common stop words (very basic list)
        $stopWords = ['is', 'what', 'the', 'a', 'an', 'and', 'or', 'nini', 'ni', 'ya', 'na', 'kwa'];
        
        $input = Str::lower($input);
        $input = preg_replace('/[^a-z0-9 ]/i', '', $input);
        $words = explode(' ', $input);
        
        return array_values(array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        }));
    }

    protected function findRelevantContent(array $keywords, string $language): string
    {
        if (empty($keywords)) return '';

        // Cache the entire active curriculum set for the current language
        $curriculums = Cache::remember("curriculum_active_{$language}", 3600, function() use ($language) {
            return Curriculum::where('is_active', true)
                ->where('language', $language)
                ->get();
        });
        
        // Simple relevance matching
        $results = $curriculums->filter(function($item) use ($keywords) {
            $itemKeywords = (array) $item->keywords;
            $matchCount = count(array_intersect($keywords, $itemKeywords));
            $item->match_count = $matchCount;
            return $matchCount > 0;
        })
        ->sortByDesc('match_count')
        ->take(2);

        if ($results->isEmpty()) {
            return '';
        }

        return $results->map(function($item) {
            return "Topic: {$item->title}\nContent: {$item->content}";
        })->implode("\n\n---\n\n");
    }
}
