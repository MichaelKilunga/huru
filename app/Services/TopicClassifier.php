<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Lightweight bilingual (Swahili / English) topic classifier.
 *
 * It scores a question against weighted keyword maps for each domain so the
 * engine can pick the right persona, the right knowledge entries and the
 * right contacts. It also flags emergencies so safety numbers are surfaced
 * first. No network call, runs in well under a millisecond.
 */
class TopicClassifier
{
    public const GENERAL = 'general';

    /** @var array<string, array{sw:string,en:string}> */
    public const CATEGORIES = [
        'legal' => ['sw' => 'Sheria na Haki', 'en' => 'Law and Rights'],
        'health' => ['sw' => 'Afya', 'en' => 'Health'],
        'agriculture' => ['sw' => 'Kilimo, Mifugo na Uvuvi', 'en' => 'Agriculture, Livestock and Fishing'],
        'education' => ['sw' => 'Elimu', 'en' => 'Education'],
        'government' => ['sw' => 'Huduma za Serikali', 'en' => 'Government Services'],
        'finance' => ['sw' => 'Fedha na Kodi', 'en' => 'Money and Tax'],
        'business' => ['sw' => 'Biashara na Ujasiriamali', 'en' => 'Business and Entrepreneurship'],
        'employment' => ['sw' => 'Ajira na Kazi', 'en' => 'Jobs and Employment'],
        'family' => ['sw' => 'Familia na Jamii', 'en' => 'Family and Community'],
        'technology' => ['sw' => 'Teknolojia na Mawasiliano', 'en' => 'Technology and Communication'],
        'transport' => ['sw' => 'Usafiri na Usalama Barabarani', 'en' => 'Transport and Road Safety'],
        'general' => ['sw' => 'Maswali ya Jumla', 'en' => 'General Questions'],
    ];

    /**
     * Keyword => weight. Keywords are matched as whole words after
     * normalisation; multi-word keywords are matched as substrings.
     *
     * @var array<string, array<string,int>>
     */
    private const KEYWORDS = [
        'legal' => [
            // Swahili
            'sheria' => 3, 'kisheria' => 3, 'haki' => 2, 'katiba' => 3, 'mahakama' => 3, 'hakimu' => 3, 'jaji' => 3,
            'wakili' => 3, 'kesi' => 3, 'mashtaka' => 3, 'kukamatwa' => 3, 'dhamana' => 3, 'polisi' => 2, 'jela' => 2,
            'gereza' => 2, 'mkataba' => 2, 'talaka' => 3, 'ndoa' => 2, 'mirathi' => 3, 'urithi' => 3, 'wosia' => 3,
            'ardhi' => 2, 'hati' => 2, 'mgogoro' => 2, 'dhuluma' => 3, 'kudhulumiwa' => 3, 'nimedhulumiwa' => 3,
            'unyanyasaji' => 3, 'ubakaji' => 3, 'wizi' => 2, 'msaada wa kisheria' => 4, 'baraza la kata' => 3,
            'baraza la ardhi' => 3, 'mahari' => 2, 'kosa' => 1, 'faini' => 2, 'rushwa' => 3, 'takukuru' => 3,
            'mtuhumiwa' => 3, 'hatia' => 2, 'ushahidi' => 2, 'kifungo' => 3, 'fidia' => 2, 'kuhukumiwa' => 3,
            // English
            'law' => 3, 'legal' => 3, 'rights' => 2, 'constitution' => 3, 'court' => 3, 'judge' => 3, 'lawyer' => 3,
            'advocate' => 3, 'case' => 2, 'arrest' => 3, 'arrested' => 3, 'bail' => 3, 'police' => 2, 'prison' => 2,
            'contract' => 2, 'divorce' => 3, 'marriage' => 2, 'inheritance' => 3, 'will' => 1, 'land' => 2,
            'title deed' => 3, 'dispute' => 2, 'harassment' => 3, 'rape' => 3, 'theft' => 2, 'legal aid' => 4,
            'corruption' => 3, 'suspect' => 2, 'evidence' => 2, 'sentence' => 2, 'compensation' => 2, 'tenant' => 2,
            'landlord' => 2, 'eviction' => 3, 'custody' => 3,
        ],
        'health' => [
            'afya' => 3, 'hospitali' => 3, 'zahanati' => 3, 'daktari' => 3, 'dawa' => 3, 'ugonjwa' => 3, 'mgonjwa' => 3,
            'homa' => 3, 'malaria' => 3, 'kikohozi' => 3, 'kuhara' => 3, 'kutapika' => 3, 'maumivu' => 2, 'mimba' => 3,
            'mjamzito' => 3, 'kujifungua' => 3, 'mtoto mchanga' => 3, 'chanjo' => 3, 'ukimwi' => 3, 'kifua kikuu' => 3,
            'kisukari' => 3, 'presha' => 3, 'shinikizo la damu' => 3, 'lishe' => 2, 'bima ya afya' => 3, 'nhif' => 3,
            'chf' => 2, 'kipindupindu' => 3, 'dengue' => 3, 'saratani' => 3, 'kansa' => 3, 'jeraha' => 2, 'uzazi' => 2,
            'uzazi wa mpango' => 3, 'kondomu' => 2, 'msongo' => 2, 'akili' => 1, 'nyoka' => 2, 'sumu' => 2,
            'health' => 3, 'hospital' => 3, 'clinic' => 3, 'doctor' => 3, 'medicine' => 3, 'drug' => 2, 'disease' => 3,
            'sick' => 2, 'fever' => 3, 'cough' => 3, 'diarrhoea' => 3, 'diarrhea' => 3, 'vomiting' => 3, 'pain' => 2,
            'pregnant' => 3, 'pregnancy' => 3, 'vaccine' => 3, 'hiv' => 3, 'aids' => 2, 'tuberculosis' => 3,
            'diabetes' => 3, 'blood pressure' => 3, 'nutrition' => 2, 'insurance' => 1, 'cholera' => 3, 'cancer' => 3,
            'injury' => 2, 'family planning' => 3, 'mental' => 2, 'snake' => 2, 'poison' => 2, 'symptoms' => 3,
        ],
        'agriculture' => [
            'kilimo' => 3, 'mkulima' => 3, 'wakulima' => 3, 'shamba' => 3, 'mazao' => 3, 'mbegu' => 3, 'mbolea' => 3,
            'mahindi' => 3, 'mpunga' => 3, 'maharage' => 3, 'mihogo' => 3, 'korosho' => 3, 'kahawa' => 3, 'pamba' => 3,
            'tumbaku' => 3, 'alizeti' => 3, 'ufuta' => 3, 'ndizi' => 2, 'nyanya' => 2, 'vitunguu' => 2, 'mvua' => 2,
            'msimu' => 2, 'kupanda' => 2, 'kuvuna' => 3, 'mavuno' => 3, 'wadudu' => 3, 'viwavijeshi' => 3, 'magonjwa ya mimea' => 3,
            'mifugo' => 3, 'ng\'ombe' => 3, 'ngombe' => 3, 'mbuzi' => 3, 'kuku' => 3, 'kondoo' => 2, 'nguruwe' => 2,
            'samaki' => 2, 'uvuvi' => 3, 'ufugaji' => 3, 'chanjo ya mifugo' => 3, 'umwagiliaji' => 3, 'bwana shamba' => 3,
            'afisa ugani' => 3, 'tari' => 2, 'soko la mazao' => 3, 'ghala' => 2, 'bei ya mazao' => 3,
            'agriculture' => 3, 'farmer' => 3, 'farm' => 3, 'crop' => 3, 'crops' => 3, 'seed' => 3, 'seeds' => 3,
            'fertilizer' => 3, 'fertiliser' => 3, 'maize' => 3, 'rice' => 2, 'beans' => 3, 'cassava' => 3, 'cashew' => 3,
            'coffee' => 2, 'cotton' => 3, 'tobacco' => 3, 'sunflower' => 3, 'harvest' => 3, 'pest' => 3, 'pests' => 3,
            'armyworm' => 3, 'livestock' => 3, 'cattle' => 3, 'cow' => 2, 'goat' => 3, 'chicken' => 3, 'poultry' => 3,
            'fishing' => 3, 'irrigation' => 3, 'extension officer' => 3, 'planting season' => 3, 'rainfall' => 2,
        ],
        'education' => [
            'elimu' => 3, 'shule' => 3, 'mwanafunzi' => 3, 'wanafunzi' => 3, 'mwalimu' => 3, 'darasa' => 3, 'mtihani' => 3,
            'necta' => 3, 'kidato' => 3, 'sekondari' => 3, 'msingi' => 2, 'chuo' => 3, 'chuo kikuu' => 3, 'mkopo wa elimu' => 3,
            'heslb' => 3, 'bodi ya mikopo' => 3, 'ada' => 2, 'matokeo' => 3, 'ufaulu' => 3, 'somo' => 2, 'masomo' => 2,
            'hisabati' => 3, 'fizikia' => 3, 'kemia' => 3, 'baiolojia' => 3, 'jiografia' => 3, 'historia' => 2,
            'kiingereza' => 2, 'kusoma' => 2, 'kuandika' => 2, 'ufundi' => 2, 'veta' => 3, 'tamisemi' => 2, 'scholarship' => 3,
            'education' => 3, 'school' => 3, 'student' => 3, 'students' => 3, 'teacher' => 3, 'class' => 2, 'exam' => 3,
            'exams' => 3, 'examination' => 3, 'form four' => 3, 'form six' => 3, 'secondary' => 3, 'primary' => 2,
            'university' => 3, 'college' => 3, 'student loan' => 3, 'loan board' => 3, 'fees' => 2, 'results' => 3,
            'mathematics' => 3, 'physics' => 3, 'chemistry' => 3, 'biology' => 3, 'geography' => 3, 'history' => 2,
            'homework' => 3, 'syllabus' => 3, 'vocational' => 3, 'explain' => 1, 'define' => 1,
        ],
        'government' => [
            'serikali' => 3, 'nida' => 3, 'kitambulisho' => 3, 'kitambulisho cha taifa' => 4, 'pasipoti' => 3, 'uhamiaji' => 3,
            'cheti cha kuzaliwa' => 4, 'rita' => 3, 'cheti cha kifo' => 3, 'leseni' => 2, 'brela' => 3, 'usajili' => 2,
            'mkurugenzi' => 2, 'halmashauri' => 3, 'mtendaji' => 3, 'diwani' => 3, 'mbunge' => 3, 'mkuu wa wilaya' => 3,
            'mkuu wa mkoa' => 3, 'wizara' => 3, 'waziri' => 3, 'rais' => 3, 'bunge' => 3, 'uchaguzi' => 3, 'kupiga kura' => 3,
            'tanesco' => 3, 'luku' => 3, 'umeme' => 3, 'maji' => 2, 'dawasa' => 3, 'tcra' => 2, 'ofisi ya' => 1,
            'huduma za serikali' => 4, 'gepg' => 3, 'malipo ya serikali' => 3, 'kodi ya majengo' => 3, 'nssf' => 3,
            'psssf' => 3, 'pensheni' => 3, 'tasaf' => 3, 'e-government' => 3, 'tehama' => 1,
            'government' => 3, 'national id' => 4, 'passport' => 3, 'immigration' => 3, 'birth certificate' => 4,
            'death certificate' => 3, 'licence' => 2, 'license' => 2, 'registration' => 2, 'council' => 2, 'ward executive' => 3,
            'councillor' => 3, 'member of parliament' => 3, 'district commissioner' => 3, 'regional commissioner' => 3,
            'ministry' => 3, 'minister' => 3, 'president' => 3, 'parliament' => 3, 'election' => 3, 'vote' => 3,
            'electricity' => 3, 'water' => 1, 'pension' => 3, 'social security' => 3, 'public service' => 2,
        ],
        'finance' => [
            'fedha' => 3, 'pesa' => 3, 'hela' => 3, 'shilingi' => 3, 'kodi' => 3, 'tra' => 3, 'tin' => 3, 'namba ya tin' => 4,
            'vat' => 3, 'ushuru' => 3, 'benki' => 3, 'akaunti' => 3, 'mkopo' => 3, 'mikopo' => 3, 'riba' => 3, 'akiba' => 3,
            'kuweka akiba' => 3, 'm-pesa' => 3, 'mpesa' => 3, 'tigo pesa' => 3, 'airtel money' => 3, 'halopesa' => 3,
            'mixx' => 2, 'yas' => 1, 'bima' => 2, 'uwekezaji' => 3, 'hisa' => 3, 'dse' => 3, 'bajeti' => 3, 'deni' => 3,
            'madeni' => 3, 'kutapeliwa' => 3, 'utapeli' => 3, 'matapeli' => 3, 'kiinua mgongo' => 3, 'mshahara' => 2,
            'dola' => 2, 'kubadilisha fedha' => 3, 'sacco' => 3, 'saccos' => 3, 'vicoba' => 3, 'kodi ya mapato' => 4,
            'money' => 3, 'cash' => 2, 'tax' => 3, 'taxes' => 3, 'bank' => 3, 'account' => 2, 'loan' => 3, 'loans' => 3,
            'interest' => 2, 'savings' => 3, 'mobile money' => 3, 'investment' => 3, 'shares' => 3, 'budget' => 3,
            'debt' => 3, 'scam' => 3, 'fraud' => 3, 'exchange rate' => 3, 'dollar' => 2, 'income tax' => 4, 'salary' => 2,
        ],
        'business' => [
            'biashara' => 3, 'mfanyabiashara' => 3, 'wafanyabiashara' => 3, 'kampuni' => 3, 'kusajili biashara' => 4,
            'leseni ya biashara' => 4, 'mtaji' => 3, 'faida' => 3, 'hasara' => 2, 'wateja' => 3, 'mteja' => 2, 'soko' => 2,
            'bei' => 2, 'kuuza' => 3, 'kununua' => 2, 'duka' => 3, 'ujasiriamali' => 3, 'mjasiriamali' => 3, 'wajasiriamali' => 3,
            'sido' => 3, 'tbs' => 3, 'tfda' => 2, 'tmda' => 3, 'kuagiza' => 2, 'kusafirisha' => 2, 'uuzaji' => 2, 'masoko' => 3,
            'mpango wa biashara' => 4, 'machinga' => 3, 'bodaboda' => 2, 'kiwanda' => 3,
            'business' => 3, 'company' => 3, 'register a business' => 4, 'business license' => 4, 'capital' => 2,
            'profit' => 3, 'loss' => 1, 'customer' => 3, 'customers' => 3, 'market' => 2, 'price' => 2, 'sell' => 3,
            'selling' => 3, 'shop' => 3, 'entrepreneur' => 3, 'entrepreneurship' => 3, 'startup' => 3, 'import' => 2,
            'export' => 2, 'marketing' => 3, 'business plan' => 4, 'supplier' => 2, 'factory' => 2,
        ],
        'employment' => [
            'ajira' => 3, 'kazi' => 2, 'mwajiri' => 3, 'mwajiriwa' => 3, 'mfanyakazi' => 3, 'wafanyakazi' => 3,
            'kufukuzwa' => 3, 'kufukuzwa kazi' => 4, 'likizo' => 3, 'likizo ya uzazi' => 4, 'mkataba wa kazi' => 4,
            'mshahara' => 3, 'kima cha chini' => 3, 'saa za kazi' => 3, 'overtime' => 3, 'cv' => 3, 'wasifu' => 3,
            'usaili' => 3, 'nafasi za kazi' => 4, 'kutafuta kazi' => 3, 'chama cha wafanyakazi' => 3, 'cma' => 3,
            'tume ya usuluhishi' => 3, 'kustaafu' => 3, 'mafao' => 3, 'wakala wa ajira' => 3, 'ajira nje ya nchi' => 4,
            'employment' => 3, 'job' => 3, 'jobs' => 3, 'employer' => 3, 'employee' => 3, 'worker' => 3, 'fired' => 3,
            'dismissed' => 3, 'dismissal' => 3, 'leave' => 2, 'maternity' => 3, 'employment contract' => 4, 'wage' => 3,
            'wages' => 3, 'minimum wage' => 4, 'working hours' => 3, 'resume' => 3, 'interview' => 3, 'vacancy' => 3,
            'vacancies' => 3, 'job search' => 3, 'trade union' => 3, 'retirement' => 3, 'benefits' => 2, 'recruitment' => 3,
        ],
        'family' => [
            'familia' => 3, 'mke' => 2, 'mume' => 2, 'wazazi' => 3, 'mzazi' => 3, 'mtoto' => 2, 'watoto' => 2, 'malezi' => 3,
            'kulea' => 3, 'mahusiano' => 3, 'mpenzi' => 3, 'uchumba' => 3, 'harusi' => 3, 'ukatili wa kijinsia' => 4,
            'ukatili' => 3, 'kupigwa' => 3, 'mume ananipiga' => 4, 'ndoa za utotoni' => 4, 'ukeketaji' => 4, 'yatima' => 3,
            'wazee' => 2, 'jamii' => 2, 'majirani' => 2, 'ugomvi' => 2, 'msaada wa kijamii' => 3, 'ustawi wa jamii' => 4,
            'dawati la jinsia' => 4, 'mimba za utotoni' => 4, 'vijana' => 2, 'msichana' => 2, 'mvulana' => 2,
            'family' => 3, 'wife' => 2, 'husband' => 2, 'parents' => 3, 'parent' => 3, 'child' => 2, 'children' => 2,
            'parenting' => 3, 'relationship' => 3, 'boyfriend' => 3, 'girlfriend' => 3, 'wedding' => 3,
            'gender based violence' => 4, 'gbv' => 4, 'domestic violence' => 4, 'abuse' => 3, 'beaten' => 3,
            'child marriage' => 4, 'fgm' => 4, 'orphan' => 3, 'elderly' => 2, 'community' => 2, 'neighbours' => 2,
            'social welfare' => 4, 'gender desk' => 4, 'teen pregnancy' => 4, 'youth' => 2,
        ],
        'technology' => [
            'simu' => 2, 'intaneti' => 3, 'mtandao' => 2, 'kompyuta' => 3, 'laini' => 3, 'kusajili laini' => 4,
            'bando' => 3, 'vifurushi' => 3, 'whatsapp' => 3, 'facebook' => 3, 'instagram' => 3, 'tiktok' => 3,
            'email' => 2, 'barua pepe' => 2, 'nywila' => 3, 'password' => 3, 'akaunti imedukuliwa' => 4, 'udukuzi' => 4,
            'kudukuliwa' => 4, 'programu' => 2, 'app' => 2, 'apu' => 2, 'kompyuta mpakato' => 3, 'laptop' => 3,
            'tcra' => 3, 'vodacom' => 3, 'tigo' => 2, 'airtel' => 2, 'halotel' => 3, 'ttcl' => 3, 'zantel' => 3,
            'mtandao wa simu' => 3, 'wifi' => 3, 'data' => 1, 'kuchaji' => 2, 'betri' => 2, 'skrini' => 2, 'ai' => 2,
            'phone' => 2, 'internet' => 3, 'computer' => 3, 'sim card' => 4, 'sim' => 2, 'bundle' => 3, 'bundles' => 3,
            'hacked' => 4, 'hacking' => 4, 'software' => 3, 'application' => 2, 'network' => 2, 'online' => 2,
            'website' => 3, 'coding' => 3, 'programming' => 3, 'charge' => 1, 'battery' => 2, 'screen' => 2,
        ],
        'transport' => [
            'usafiri' => 3, 'daladala' => 3, 'basi' => 3, 'mabasi' => 3, 'bodaboda' => 3, 'bajaji' => 3, 'teksi' => 3,
            'gari' => 2, 'leseni ya udereva' => 4, 'udereva' => 3, 'ajali' => 3, 'barabara' => 2, 'trafiki' => 3,
            'usalama barabarani' => 4, 'faini ya trafiki' => 4, 'bima ya gari' => 3, 'latra' => 3, 'sumatra' => 2,
            'tanroads' => 3, 'treni' => 3, 'sgr' => 3, 'reli' => 3, 'ndege' => 3, 'uwanja wa ndege' => 3, 'meli' => 3,
            'kivuko' => 3, 'bandari' => 2, 'mwendokasi' => 3, 'dart' => 2, 'nauli' => 3, 'tiketi' => 2, 'kuhamisha umiliki wa gari' => 4,
            'transport' => 3, 'bus' => 3, 'buses' => 3, 'motorcycle' => 3, 'taxi' => 3, 'car' => 2, 'driving licence' => 4,
            'driving license' => 4, 'driver' => 2, 'accident' => 3, 'road' => 2, 'traffic' => 3, 'road safety' => 4,
            'traffic fine' => 4, 'car insurance' => 3, 'train' => 3, 'railway' => 3, 'flight' => 3, 'airport' => 3,
            'ferry' => 3, 'port' => 1, 'fare' => 3, 'ticket' => 2, 'vehicle' => 2, 'registration card' => 3,
        ],
    ];

    /** Patterns that indicate a life-threatening or urgent situation. */
    private const EMERGENCY = [
        'dharura', 'emergency', 'ajali', 'accident', 'moto', 'fire', 'anakufa', 'dying', 'amezimia', 'unconscious',
        'kujiua', 'suicide', 'nataka kufa', 'kill myself', 'ananipiga sasa', 'anataka kuniua', 'damu nyingi', 'bleeding',
        'sumu', 'poison', 'nyoka ameniuma', 'snake bite', 'kupumua' , 'breathe', 'mafuriko', 'flood', 'amepotea', 'missing child',
        'mtoto amepotea', 'kutekwa', 'kidnapped', 'wezi wameingia', 'robbery', 'haraka sana', 'help me now',
    ];

    /**
     * @return array{category:string, confidence:float, scores:array<string,int>, is_emergency:bool}
     */
    public function classify(string $text): array
    {
        $normalized = $this->normalize($text);
        $words = array_filter(explode(' ', $normalized));
        $wordSet = array_flip($words);

        $scores = [];
        foreach (self::KEYWORDS as $category => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword => $weight) {
                if (str_contains($keyword, ' ') || str_contains($keyword, '-') || str_contains($keyword, "'")) {
                    if (str_contains($normalized, $keyword)) {
                        $score += $weight + 1; // phrases are strong signals
                    }
                } elseif (isset($wordSet[$keyword])) {
                    $score += $weight;
                }
            }
            if ($score > 0) {
                $scores[$category] = $score;
            }
        }

        arsort($scores);
        $top = array_key_first($scores);
        $topScore = $top ? $scores[$top] : 0;
        $total = array_sum($scores) ?: 1;

        $category = ($top && $topScore >= 2) ? $top : self::GENERAL;
        $confidence = $top ? round($topScore / $total, 2) : 0.0;

        return [
            'category' => $category,
            'confidence' => $confidence,
            'scores' => $scores,
            'is_emergency' => $this->isEmergency($normalized),
        ];
    }

    public function isEmergency(string $normalizedText): bool
    {
        foreach (self::EMERGENCY as $needle) {
            if (str_contains($normalizedText, $needle)) {
                return true;
            }
        }

        return false;
    }

    public static function label(string $category, string $language = 'sw'): string
    {
        $labels = self::CATEGORIES[$category] ?? self::CATEGORIES[self::GENERAL];

        return $labels[$language === 'en' ? 'en' : 'sw'];
    }

    public static function keys(): array
    {
        return array_keys(self::CATEGORIES);
    }

    private function normalize(string $text): string
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^\p{L}\p{N}\'\- ]+/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return ' ' . trim($text) . ' ';
    }
}
