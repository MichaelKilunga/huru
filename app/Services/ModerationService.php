<?php

namespace App\Services;

use Illuminate\Support\Str;

class ModerationService
{
    /** Abusive terms in Swahili and English. Matched as whole words. */
    protected array $forbiddenWords = [
        'pumbavu', 'mjinga', 'mshenzi', 'fala', 'mavi', 'mbwa wewe', 'kuma', 'mboro', 'shoga', 'malaya', 'kahaba', 'takataka',
        'fuck', 'fucking', 'shit', 'idiot', 'stupid', 'bastard', 'bitch', 'asshole', 'dick', 'pussy', 'whore', 'slut', 'nigger',
    ];

    public function isAbusive(string $text): bool
    {
        $text = Str::lower($text);

        foreach ($this->forbiddenWords as $word) {
            if (preg_match('/(?<![\p{L}\p{N}])' . preg_quote($word, '/') . '(?![\p{L}\p{N}])/u', $text)) {
                return true;
            }
        }

        return false;
    }

    public function getWarningMessage(string $language, int $abuseCount, int $limit): string
    {
        $left = max(0, $limit - $abuseCount);

        if ($language === 'sw') {
            return "ONYO: Lugha ya matusi haikubaliki kwenye huduma hii. Hili ni onyo namba {$abuseCount}. Ukiendelea utafungiwa (nafasi zilizobaki: {$left}).";
        }

        return "WARNING: Abusive language is not accepted on this service. This is warning #{$abuseCount}. Continued abuse will lead to a ban ({$left} chances left).";
    }

    public function getBanMessage(string $language): string
    {
        if ($language === 'sw') {
            return 'HUDUMA IMEFUNGWA: Umefungiwa kutumia huduma hii kwa kukiuka masharti (lugha ya matusi). Wasiliana nasi ikiwa unaamini hili ni kosa.';
        }

        return 'SERVICE BANNED: You have been blocked from this service for violating our terms (abusive language). Contact us if you believe this is a mistake.';
    }
}
