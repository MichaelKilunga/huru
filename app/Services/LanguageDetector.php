<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Decides whether a message is Swahili ('sw') or English ('en').
 *
 * Strategy: weighted function-word lists first; if that is a tie, fall back
 * to a morphological heuristic (Swahili words overwhelmingly end in a vowel
 * and use characteristic prefixes). Returns null only for empty input.
 */
class LanguageDetector
{
    private const SW = [
        'na' => 1, 'ya' => 1, 'wa' => 1, 'za' => 1, 'la' => 1, 'cha' => 1, 'vya' => 1, 'kwa' => 2, 'ni' => 1, 'si' => 1,
        'nini' => 3, 'vipi' => 3, 'gani' => 3, 'wapi' => 3, 'lini' => 3, 'nani' => 3, 'mbona' => 3, 'kwanini' => 3, 'kwa nini' => 3,
        'je' => 2, 'habari' => 2, 'naomba' => 3, 'nataka' => 3, 'nahitaji' => 3, 'msaada' => 2, 'tafadhali' => 3, 'asante' => 3,
        'ninaweza' => 3, 'naweza' => 3, 'unaweza' => 3, 'nifanye' => 3, 'nifanyeje' => 3, 'kama' => 1, 'jinsi' => 2, 'hii' => 2,
        'hiyo' => 2, 'hili' => 2, 'hiki' => 2, 'yangu' => 2, 'wangu' => 2, 'langu' => 2, 'changu' => 2, 'zangu' => 2, 'yake' => 2,
        'wake' => 1, 'mimi' => 2, 'wewe' => 2, 'yeye' => 2, 'sisi' => 2, 'ninyi' => 2, 'wao' => 2, 'sana' => 2, 'tu' => 1,
        'lakini' => 2, 'au' => 1, 'pia' => 2, 'kuhusu' => 3, 'katika' => 3, 'kutoka' => 3, 'hadi' => 2, 'mpaka' => 2,
        'sasa' => 2, 'leo' => 2, 'kesho' => 2, 'jana' => 2, 'sheria' => 2, 'haki' => 1, 'mtoto' => 2, 'watu' => 2, 'mtu' => 2,
        'nchi' => 2, 'serikali' => 2, 'shilingi' => 2, 'pesa' => 2, 'kazi' => 1, 'shule' => 2, 'nyumba' => 2, 'ardhi' => 2,
        'ndoa' => 2, 'afya' => 2, 'dawa' => 2, 'polisi' => 1, 'eleza' => 3, 'nieleze' => 3, 'niambie' => 3, 'fafanua' => 3,
        'maana' => 2, 'ipi' => 2, 'zipi' => 2, 'ngapi' => 3, 'kiasi' => 2, 'mara' => 1, 'bado' => 2, 'tayari' => 2,
    ];

    private const EN = [
        'the' => 2, 'is' => 1, 'are' => 1, 'and' => 1, 'of' => 1, 'to' => 1, 'in' => 1, 'for' => 1, 'what' => 3, 'how' => 3,
        'why' => 3, 'when' => 3, 'where' => 3, 'who' => 3, 'which' => 3, 'can' => 2, 'could' => 2, 'should' => 2, 'would' => 2,
        'do' => 1, 'does' => 2, 'did' => 2, 'i' => 1, 'my' => 2, 'me' => 2, 'you' => 2, 'your' => 2, 'we' => 2, 'our' => 2,
        'they' => 2, 'their' => 2, 'it' => 1, 'this' => 2, 'that' => 2, 'these' => 2, 'with' => 2, 'from' => 2, 'about' => 3,
        'please' => 3, 'help' => 2, 'need' => 2, 'want' => 2, 'explain' => 3, 'tell' => 2, 'describe' => 3, 'define' => 3,
        'difference' => 3, 'between' => 3, 'have' => 2, 'has' => 2, 'been' => 2, 'was' => 2, 'were' => 2, 'will' => 1,
        'not' => 2, 'but' => 2, 'or' => 1, 'also' => 2, 'there' => 2, 'here' => 2, 'now' => 2, 'today' => 2, 'tomorrow' => 2,
        'law' => 2, 'rights' => 2, 'child' => 2, 'people' => 2, 'person' => 2, 'country' => 2, 'government' => 2,
        'money' => 2, 'work' => 1, 'school' => 2, 'house' => 2, 'land' => 1, 'marriage' => 2, 'health' => 2, 'medicine' => 2,
        'much' => 2, 'many' => 2, 'if' => 2, 'because' => 3, 'get' => 1, 'know' => 2, 'think' => 2, 'any' => 2, 'some' => 2,
    ];

    public function detect(string $text): ?string
    {
        $lower = Str::lower($text);
        $words = preg_split('/[^\p{L}\p{N}\']+/u', $lower, -1, PREG_SPLIT_NO_EMPTY);

        if (empty($words)) {
            return null;
        }

        $sw = 0;
        $en = 0;
        foreach ($words as $w) {
            $sw += self::SW[$w] ?? 0;
            $en += self::EN[$w] ?? 0;
        }
        if (str_contains($lower, 'kwa nini')) {
            $sw += 3;
        }

        if ($sw !== $en) {
            return $sw > $en ? 'sw' : 'en';
        }

        // Morphological tie-breaker.
        $vowelEnding = 0;
        $swPrefix = 0;
        foreach ($words as $w) {
            if (strlen($w) < 3) {
                continue;
            }
            if (preg_match('/[aeiou]$/', $w)) {
                $vowelEnding++;
            }
            if (preg_match('/^(ku|ni|u|a|tu|m|wa|ki|vi|ma|mi|ha|si|nime|ume|ame|tume|wame|nina|una|ana|tuna|wana|nita|uta|ata)/', $w)) {
                $swPrefix++;
            }
        }
        $considered = max(1, count(array_filter($words, fn ($w) => strlen($w) >= 3)));
        $ratio = $vowelEnding / $considered;

        if ($ratio >= 0.7 || ($ratio >= 0.55 && $swPrefix / $considered >= 0.4)) {
            return 'sw';
        }
        if ($ratio <= 0.35) {
            return 'en';
        }

        return null;
    }
}
