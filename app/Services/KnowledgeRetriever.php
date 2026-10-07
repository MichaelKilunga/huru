<?php

namespace App\Services;

use App\Models\KnowledgeEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Scores curated knowledge entries against a question.
 *
 * Tokens are normalised and lightly stemmed for both Swahili and English
 * so that "nimekamatwa", "kukamatwa" and "kamatwa" all meet the keyword
 * "kamat". Entries in the citizen's language are preferred; other-language
 * entries are still searched at a discount so a Swahili question can draw
 * on an English-only fact block when nothing else matches.
 */
class KnowledgeRetriever
{
    private const STOP = [
        // Swahili
        'na', 'ya', 'wa', 'za', 'la', 'cha', 'vya', 'kwa', 'ni', 'si', 'je', 'nini', 'vipi', 'gani', 'wapi', 'lini', 'nani',
        'mbona', 'kama', 'jinsi', 'hii', 'hiyo', 'hili', 'hiki', 'yangu', 'wangu', 'langu', 'mimi', 'wewe', 'yeye', 'sisi',
        'wao', 'sana', 'tu', 'lakini', 'au', 'pia', 'kuhusu', 'katika', 'kutoka', 'hadi', 'sasa', 'leo', 'naomba', 'nataka',
        'nahitaji', 'tafadhali', 'asante', 'habari', 'huru', 'msaada', 'nieleze', 'niambie', 'eleza', 'fafanua', 'maana',
        'ipi', 'zipi', 'ngapi', 'kiasi', 'mara', 'bado', 'nipo', 'niko', 'ninaweza', 'naweza', 'unaweza', 'nifanye', 'nifanyeje',
        // English
        'the', 'is', 'are', 'and', 'of', 'to', 'in', 'for', 'what', 'how', 'why', 'when', 'where', 'who', 'which', 'can',
        'could', 'should', 'would', 'do', 'does', 'did', 'i', 'my', 'me', 'you', 'your', 'we', 'our', 'they', 'their', 'it',
        'this', 'that', 'these', 'with', 'from', 'about', 'please', 'help', 'need', 'want', 'explain', 'tell', 'describe',
        'define', 'have', 'has', 'been', 'was', 'were', 'will', 'not', 'but', 'or', 'also', 'there', 'here', 'now', 'a', 'an',
        'am', 'be', 'on', 'at', 'by', 'as', 'if', 'so', 'get', 'know', 'any', 'some', 'much', 'many',
    ];

    /**
     * @return Collection<int, KnowledgeEntry> ordered by relevance, each with ->score
     */
    public function search(string $question, string $language, ?string $category = null, int $limit = 2): Collection
    {
        if ($limit <= 0) {
            return collect();
        }

        $queryTokens = $this->tokens($question);
        if (empty($queryTokens)) {
            return collect();
        }
        $querySet = array_flip($queryTokens);

        $entries = Cache::remember('huru.knowledge.active', 3600, fn () => KnowledgeEntry::query()
            ->where('is_active', true)
            ->get()
            ->map(function (KnowledgeEntry $e) {
                // Precompute token sets once per cache cycle.
                $e->setAttribute('_kw', array_flip($this->tokens(implode(' ', (array) $e->keywords))));
                $e->setAttribute('_title', array_flip($this->tokens($e->title)));
                $e->setAttribute('_body', array_flip($this->tokens(Str::limit($e->content, 1500, ''))));

                return $e;
            }));

        $scored = $entries->map(function (KnowledgeEntry $e) use ($querySet, $language, $category) {
            $score = 0;
            foreach ($querySet as $tok => $_) {
                if (isset($e->_kw[$tok])) {
                    $score += 3;
                }
                if (isset($e->_title[$tok])) {
                    $score += 2;
                }
                if (isset($e->_body[$tok])) {
                    $score += 1;
                }
            }
            if ($score === 0) {
                return null;
            }
            if ($category && $e->category === $category) {
                $score += 2;
            }
            if ($e->language !== $language) {
                $score = (int) ceil($score * 0.6);
            }
            $e->setAttribute('score', $score);

            return $e;
        })->filter()->sortByDesc('score')->values();

        return $scored->take($limit);
    }

    /** @return string[] unique normalised, stemmed tokens */
    public function tokens(string $text): array
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^\p{L}\p{N} ]+/u', ' ', $text);
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);

        $out = [];
        foreach ($words as $w) {
            if (strlen($w) < 3 || in_array($w, self::STOP, true)) {
                continue;
            }
            $out[$w] = true;
            $stem = $this->stem($w);
            if ($stem !== $w && strlen($stem) >= 3) {
                $out[$stem] = true;
            }
        }

        return array_keys($out);
    }

    /**
     * Very small bilingual stemmer. Swahili: strip subject/tense prefixes and
     * common suffixes. English: strip plural/participle suffixes.
     */
    public function stem(string $w): string
    {
        $len = strlen($w);
        if ($len <= 4) {
            return $w;
        }

        // Swahili prefixes (longest first)
        foreach (['nime', 'ume', 'ame', 'tume', 'mme', 'wame', 'nina', 'una', 'ana', 'tuna', 'mna', 'wana', 'nita', 'uta', 'ata', 'tuta', 'wata', 'nili', 'uli', 'ali', 'tuli', 'wali', 'kuna', 'kuwa', 'ku', 'wa', 'ki', 'vi', 'ma', 'mi', 'u'] as $p) {
            if (str_starts_with($w, $p) && strlen($w) - strlen($p) >= 4) {
                $w = substr($w, strlen($p));
                break;
            }
        }
        // Swahili suffixes
        foreach (['wa', 'ni', 'ji', 'ka', 'ia', 'ea'] as $s) {
            if (str_ends_with($w, $s) && strlen($w) - strlen($s) >= 4) {
                $w = substr($w, 0, -strlen($s));
                break;
            }
        }
        // English suffixes
        foreach (['ies', 'ing', 'ed', 'es', 's'] as $s) {
            if (str_ends_with($w, $s) && strlen($w) - strlen($s) >= 4) {
                $w = substr($w, 0, -strlen($s));
                if ($s === 'ies') {
                    $w .= 'y';
                }
                break;
            }
        }

        return $w;
    }
}
