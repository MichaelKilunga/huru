<?php

namespace App\Services;

use Illuminate\Support\Str;

class LanguageDetector
{
    /**
     * Detects if the given text is primarily Swahili ('sw') or English ('en').
     * If undetermined, returns null.
     */
    public function detect(string $text): ?string
    {
        $swahiliKeywords = ['nini', 'vipi', 'gani', 'habari', 'naomba', 'msaada', 'kuelewa', 'maada', 'mwalimu', 'kufundisha', 'eleza', 'kiswahili', 'kwa', 'ya', 'na', 'ni', 'wa', 'za', 'kama', 'jinsi', 'mtihani', 'swali', 'mbona', 'lini', 'wapi', 'nani', 'aina', 'tofauti', 'sheria', 'katiba', 'haki', 'mahakama', 'polisi', 'mkataba', 'ajira', 'ndoa', 'ardhi', 'kesi', 'hatia', 'kisheria', 'bunge', 'wakili'];
        $englishKeywords = ['what', 'how', 'why', 'explain', 'teach', 'help', 'matter', 'physics', 'biology', 'chemistry', 'science', 'is', 'are', 'the', 'of', 'and', 'to', 'in', 'for', 'who', 'when', 'where', 'describe', 'define', 'difference', 'types', 'law', 'constitution', 'rights', 'court', 'police', 'contract', 'employment', 'marriage', 'land', 'case', 'guilty', 'legal', 'parliament', 'advocate', 'lawyer', 'lease'];
        
        $text = Str::lower($text);
        
        $swMatches = 0;
        $enMatches = 0;
        
        // Extract words, ignoring punctuation
        $words = preg_split('/\W+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        if (empty($words)) {
            return null;
        }
        
        foreach ($words as $word) {
            if (in_array($word, $swahiliKeywords)) $swMatches++;
            if (in_array($word, $englishKeywords)) $enMatches++;
        }
        
        if ($swMatches > $enMatches) return 'sw';
        if ($enMatches > $swMatches) return 'en';

        return null;
    }
}
