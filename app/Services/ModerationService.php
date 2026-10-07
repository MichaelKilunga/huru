<?php

namespace App\Services;

use Illuminate\Support\Str;

class ModerationService
{
    /**
     * List of abusive/forbidden keywords in Swahili and English.
     */
    protected array $forbiddenWords = [
        // Swahili (General offensive)
        'pumbavu', 'mjinga', 'mshenzi', 'fala', 'mavi', 'umaarufu', 'mbwa', 'kuma', 'mboro', 'shoga', 'ngono', 'malaya',
        
        // English (General offensive)
        'fuck', 'shit', 'idiot', 'stupid', 'bastard', 'bitch', 'asshole', 'dick', 'pussy', 'sex', 'porn', 'abuse',
    ];

    /**
     * Check if the given text contains any forbidden words.
     * 
     * @param string $text
     * @return bool
     */
    public function isAbusive(string $text): bool
    {
        $text = Str::lower($text);
        
        foreach ($this->forbiddenWords as $word) {
            // Using regex to match whole words only to avoid false positives (e.g. "asset" containing "ass")
            if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $text)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the warning message based on the language.
     * 
     * @param string $language
     * @param int $abuseCount
     * @return string
     */
    public function getWarningMessage(string $language, int $abuseCount): string
    {
        if ($language === 'sw') {
            return "ONYO: Lugha ya matusi haikubaliki. Huu ni onyo lako la " . $abuseCount . ". Utafungiwa huduma ukiendelea.";
        }

        return "WARNING: Abusive language is not accepted. This is your warning #" . $abuseCount . ". You will be banned from this service if this continues.";
    }

    /**
     * Get the ban message.
     */
    public function getBanMessage(string $language): string
    {
        if ($language === 'sw') {
            return "HUDUMA IMEFUNGIWA: Umefungiwa kupata huduma hii kutokana na kukiuka vigezo na masharti (matusi).";
        }

        return "SERVICE BANNED: You have been banned from using this service due to violating our terms (abusive language).";
    }
}
