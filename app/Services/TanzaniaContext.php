<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Everything the engine should know about Tanzania before it answers:
 * where the citizen is, what day it is in East Africa, which currency and
 * institutions apply, and how each topic should be handled locally.
 */
class TanzaniaContext
{
    public const TIMEZONE = 'Africa/Dar_es_Salaam';

    /** The 31 regions (26 Mainland + 5 Zanzibar). */
    public const REGIONS = [
        'arusha', 'dar es salaam', 'dodoma', 'geita', 'iringa', 'kagera', 'katavi', 'kigoma', 'kilimanjaro',
        'lindi', 'manyara', 'mara', 'mbeya', 'morogoro', 'mtwara', 'mwanza', 'njombe', 'pwani', 'rukwa', 'ruvuma',
        'shinyanga', 'simiyu', 'singida', 'songwe', 'tabora', 'tanga',
        'kaskazini pemba', 'kusini pemba', 'kaskazini unguja', 'kusini unguja', 'mjini magharibi',
    ];

    /** Common aliases citizens actually type. */
    private const REGION_ALIASES = [
        'dar' => 'dar es salaam', 'dsm' => 'dar es salaam', 'bongo' => 'dar es salaam', 'coast' => 'pwani',
        'zanzibar' => 'mjini magharibi', 'unguja' => 'mjini magharibi', 'pemba' => 'kaskazini pemba',
        'moshi' => 'kilimanjaro', 'kili' => 'kilimanjaro',
    ];

    /** District (or well-known town) => region. Not exhaustive, covers what people write. */
    private const DISTRICTS = [
        // Dar es Salaam
        'ilala' => 'dar es salaam', 'kinondoni' => 'dar es salaam', 'temeke' => 'dar es salaam',
        'ubungo' => 'dar es salaam', 'kigamboni' => 'dar es salaam',
        // Arusha
        'meru' => 'arusha', 'monduli' => 'arusha', 'karatu' => 'arusha', 'longido' => 'arusha', 'ngorongoro' => 'arusha',
        // Dodoma
        'bahi' => 'dodoma', 'chamwino' => 'dodoma', 'kondoa' => 'dodoma', 'kongwa' => 'dodoma', 'mpwapwa' => 'dodoma', 'chemba' => 'dodoma',
        // Mwanza
        'nyamagana' => 'mwanza', 'ilemela' => 'mwanza', 'magu' => 'mwanza', 'misungwi' => 'mwanza', 'sengerema' => 'mwanza', 'ukerewe' => 'mwanza', 'kwimba' => 'mwanza',
        // Mbeya
        'rungwe' => 'mbeya', 'kyela' => 'mbeya', 'chunya' => 'mbeya', 'mbarali' => 'mbeya', 'busokelo' => 'mbeya',
        // Kilimanjaro
        'moshi' => 'kilimanjaro', 'hai' => 'kilimanjaro', 'rombo' => 'kilimanjaro', 'same' => 'kilimanjaro', 'mwanga' => 'kilimanjaro', 'siha' => 'kilimanjaro',
        // Tanga
        'muheza' => 'tanga', 'korogwe' => 'tanga', 'lushoto' => 'tanga', 'handeni' => 'tanga', 'pangani' => 'tanga', 'mkinga' => 'tanga', 'kilindi' => 'tanga',
        // Morogoro
        'kilosa' => 'morogoro', 'kilombero' => 'morogoro', 'ulanga' => 'morogoro', 'mvomero' => 'morogoro', 'gairo' => 'morogoro', 'malinyi' => 'morogoro', 'ifakara' => 'morogoro',
        // Shinyanga
        'kahama' => 'shinyanga', 'kishapu' => 'shinyanga',
        // Geita
        'chato' => 'geita', 'bukombe' => 'geita', 'mbogwe' => 'geita', 'nyanghwale' => 'geita',
        // Kagera
        'bukoba' => 'kagera', 'muleba' => 'kagera', 'karagwe' => 'kagera', 'ngara' => 'kagera', 'biharamulo' => 'kagera', 'missenyi' => 'kagera', 'kyerwa' => 'kagera',
        // Kigoma
        'kasulu' => 'kigoma', 'kibondo' => 'kigoma', 'uvinza' => 'kigoma', 'buhigwe' => 'kigoma', 'kakonko' => 'kigoma',
        // Mara
        'musoma' => 'mara', 'tarime' => 'mara', 'serengeti' => 'mara', 'bunda' => 'mara', 'rorya' => 'mara', 'butiama' => 'mara',
        // Tabora
        'nzega' => 'tabora', 'igunga' => 'tabora', 'urambo' => 'tabora', 'sikonge' => 'tabora', 'uyui' => 'tabora', 'kaliua' => 'tabora',
        // Iringa / Njombe
        'kilolo' => 'iringa', 'mufindi' => 'iringa', 'makete' => 'njombe', 'wangingombe' => 'njombe', 'ludewa' => 'njombe', 'makambako' => 'njombe',
        // Ruvuma
        'songea' => 'ruvuma', 'mbinga' => 'ruvuma', 'tunduru' => 'ruvuma', 'namtumbo' => 'ruvuma', 'nyasa' => 'ruvuma', 'madaba' => 'ruvuma',
        // Mtwara / Lindi
        'masasi' => 'mtwara', 'newala' => 'mtwara', 'nanyumbu' => 'mtwara', 'tandahimba' => 'mtwara',
        'kilwa' => 'lindi', 'nachingwea' => 'lindi', 'liwale' => 'lindi', 'ruangwa' => 'lindi',
        // Pwani
        'kibaha' => 'pwani', 'bagamoyo' => 'pwani', 'kisarawe' => 'pwani', 'mkuranga' => 'pwani', 'rufiji' => 'pwani', 'mafia' => 'pwani', 'kibiti' => 'pwani', 'chalinze' => 'pwani',
        // Singida / Manyara
        'manyoni' => 'singida', 'iramba' => 'singida', 'ikungi' => 'singida', 'mkalama' => 'singida', 'itigi' => 'singida',
        'babati' => 'manyara', 'hanang' => 'manyara', 'mbulu' => 'manyara', 'simanjiro' => 'manyara', 'kiteto' => 'manyara',
        // Rukwa / Katavi / Songwe
        'sumbawanga' => 'rukwa', 'nkasi' => 'rukwa', 'kalambo' => 'rukwa',
        'mpanda' => 'katavi', 'mlele' => 'katavi', 'tanganyika' => 'katavi',
        'tunduma' => 'songwe', 'momba' => 'songwe', 'ileje' => 'songwe', 'mbozi' => 'songwe', 'vwawa' => 'songwe',
        // Simiyu
        'bariadi' => 'simiyu', 'maswa' => 'simiyu', 'meatu' => 'simiyu', 'itilima' => 'simiyu', 'busega' => 'simiyu',
        // Zanzibar
        'wete' => 'kaskazini pemba', 'micheweni' => 'kaskazini pemba', 'chake chake' => 'kusini pemba', 'mkoani' => 'kusini pemba',
        'mjini' => 'mjini magharibi', 'magharibi' => 'mjini magharibi', 'stone town' => 'mjini magharibi',
    ];

    /** Well-known neighbourhoods / wards => [district, region]. Mostly Dar es Salaam, where most web traffic comes from. */
    private const AREAS = [
        'sinza' => ['ubungo', 'dar es salaam'], 'mwenge' => ['kinondoni', 'dar es salaam'], 'mbezi' => ['ubungo', 'dar es salaam'],
        'tegeta' => ['kinondoni', 'dar es salaam'], 'kawe' => ['kinondoni', 'dar es salaam'], 'mikocheni' => ['kinondoni', 'dar es salaam'],
        'masaki' => ['kinondoni', 'dar es salaam'], 'oysterbay' => ['kinondoni', 'dar es salaam'], 'kijitonyama' => ['kinondoni', 'dar es salaam'],
        'magomeni' => ['kinondoni', 'dar es salaam'], 'tandale' => ['kinondoni', 'dar es salaam'], 'manzese' => ['ubungo', 'dar es salaam'],
        'mabibo' => ['ubungo', 'dar es salaam'], 'kimara' => ['ubungo', 'dar es salaam'], 'kibamba' => ['ubungo', 'dar es salaam'],
        'goba' => ['ubungo', 'dar es salaam'], 'makongo' => ['ubungo', 'dar es salaam'], 'mburahati' => ['ubungo', 'dar es salaam'],
        'kariakoo' => ['ilala', 'dar es salaam'], 'upanga' => ['ilala', 'dar es salaam'], 'buguruni' => ['ilala', 'dar es salaam'],
        'tabata' => ['ilala', 'dar es salaam'], 'segerea' => ['ilala', 'dar es salaam'], 'ukonga' => ['ilala', 'dar es salaam'],
        'gongo la mboto' => ['ilala', 'dar es salaam'], 'kinyerezi' => ['ilala', 'dar es salaam'], 'vingunguti' => ['ilala', 'dar es salaam'],
        'mbagala' => ['temeke', 'dar es salaam'], 'tandika' => ['temeke', 'dar es salaam'], 'kurasini' => ['temeke', 'dar es salaam'],
        'changombe' => ['temeke', 'dar es salaam'], 'chamazi' => ['temeke', 'dar es salaam'], 'kigamboni' => ['kigamboni', 'dar es salaam'],
        'posta' => ['ilala', 'dar es salaam'], 'mnazi mmoja' => ['ilala', 'dar es salaam'],
        'sakina' => ['arusha', 'arusha'], 'njiro' => ['arusha', 'arusha'], 'ngaramtoni' => ['arusha', 'arusha'],
        'nyakato' => ['ilemela', 'mwanza'], 'pasiansi' => ['ilemela', 'mwanza'], 'igoma' => ['nyamagana', 'mwanza'],
        'uyole' => ['mbeya', 'mbeya'], 'mwanjelwa' => ['mbeya', 'mbeya'], 'majengo' => ['moshi', 'kilimanjaro'],
        'mtumba' => ['dodoma', 'dodoma'], 'nzuguni' => ['dodoma', 'dodoma'],
    ];

    /**
     * Detect region / district / area mentioned in free text.
     *
     * @return array{region:?string,district:?string,area:?string}|null
     */
    public function detectLocation(string $text): ?array
    {
        $norm = ' ' . trim(preg_replace('/\s+/', ' ', preg_replace('/[^\p{L}\p{N} ]+/u', ' ', Str::lower($text)))) . ' ';

        $result = ['region' => null, 'district' => null, 'area' => null];

        foreach (self::AREAS as $area => [$district, $region]) {
            if (str_contains($norm, " {$area} ")) {
                $result = ['region' => $region, 'district' => $district, 'area' => $area];
                break;
            }
        }

        if (! $result['district']) {
            foreach (self::DISTRICTS as $district => $region) {
                if (str_contains($norm, " {$district} ")) {
                    $result['district'] = $district;
                    $result['region'] = $region;
                    break;
                }
            }
        }

        if (! $result['region']) {
            foreach (self::REGIONS as $region) {
                if (str_contains($norm, " {$region} ")) {
                    $result['region'] = $region;
                    break;
                }
            }
        }

        if (! $result['region']) {
            foreach (self::REGION_ALIASES as $alias => $region) {
                if (str_contains($norm, " {$alias} ")) {
                    $result['region'] = $region;
                    break;
                }
            }
        }

        return $result['region'] ? $result : null;
    }

    public function now(): Carbon
    {
        return Carbon::now(self::TIMEZONE);
    }

    /** Country facts the engine must assume, in the citizen's language. */
    public function localeBlock(string $language): string
    {
        $now = $this->now();

        if ($language === 'en') {
            return "COUNTRY CONTEXT (assume these facts):\n"
                . '- Country: United Republic of Tanzania (Mainland and Zanzibar). Today is ' . $now->format('l, j F Y') . ', time ' . $now->format('H:i') . " East Africa Time.\n"
                . "- Capital: Dodoma (seat of government and parliament). Commercial capital and largest city: Dar es Salaam.\n"
                . "- Currency: Tanzanian Shilling (TZS). Never quote prices in other currencies unless asked.\n"
                . "- Languages: Swahili (national) and English (official). Many citizens read Swahili more comfortably.\n"
                . "- Mobile money (M-Pesa, Tigo Pesa/Mixx by Yas, Airtel Money, HaloPesa) is the everyday payment method. Government payments go through GePG control numbers.\n"
                . "- Local government chain: Village/Mtaa Executive Officer, Ward Executive Officer (WEO), District Council/Commissioner, Regional Commissioner. Most everyday problems start at the ward office.\n"
                . "- Legal system: common law with the Constitution of 1977; primary courts, district courts, High Court, Court of Appeal. Ward tribunals and District Land and Housing Tribunals handle local disputes.\n"
                . "- Emergency numbers: Police 112, Fire 114, Ambulance 115, Child helpline 116.\n";
        }

        return "MUKTADHA WA NCHI (chukulia haya kuwa ukweli):\n"
            . '- Nchi: Jamhuri ya Muungano wa Tanzania (Bara na Zanzibar). Leo ni ' . $this->swahiliDate($now) . ', saa ' . $now->format('H:i') . " saa za Afrika Mashariki.\n"
            . "- Makao makuu: Dodoma (Serikali na Bunge). Jiji kubwa la kibiashara: Dar es Salaam.\n"
            . "- Sarafu: Shilingi ya Tanzania (TZS). Usitaje bei kwa sarafu nyingine isipokuwa ukiulizwa.\n"
            . "- Lugha: Kiswahili (lugha ya taifa) na Kiingereza (lugha rasmi). Wananchi wengi husoma Kiswahili kwa urahisi zaidi.\n"
            . "- Malipo ya kila siku ni kwa pesa za simu (M-Pesa, Mixx by Yas/Tigo Pesa, Airtel Money, HaloPesa). Malipo ya serikali hufanywa kwa namba ya kumbukumbu (control number) ya GePG.\n"
            . "- Ngazi za serikali za mitaa: Mtendaji wa Kijiji/Mtaa, Mtendaji wa Kata (WEO), Halmashauri/Mkuu wa Wilaya, Mkuu wa Mkoa. Matatizo mengi ya kila siku huanzia ofisi ya kata.\n"
            . "- Mfumo wa sheria: Katiba ya 1977; mahakama za mwanzo, za wilaya, Mahakama Kuu, Mahakama ya Rufani. Mabaraza ya kata na Mabaraza ya Ardhi na Nyumba ya Wilaya hushughulikia migogoro ya karibu.\n"
            . "- Namba za dharura: Polisi 112, Zimamoto 114, Gari la wagonjwa 115, Msaada kwa mtoto 116.\n";
    }

    /** Topic-specific handling rules, grounded in how things work in Tanzania. */
    public function categoryGuidance(string $category, string $language): string
    {
        $sw = [
            'legal' => "- Rejea sheria za Tanzania (Katiba 1977, Sheria ya Ndoa 1971, Sheria ya Ardhi Sura 113, Sheria ya Ajira na Mahusiano Kazini Sura 366, Sheria ya Mwenendo wa Makosa ya Jinai, Sheria ya Mtoto 2009). Toa hatua za vitendo: wapi pa kuanzia (mtendaji wa kata, baraza la kata, polisi, dawati la jinsia, baraza la ardhi, mahakama ya mwanzo). Kama hali ni nzito mshauri aonane na mtoa msaada wa kisheria au wakili. Usijifanye wakili wake; eleza kuwa haya ni maelezo ya jumla.",
            'health' => "- Wewe si daktari. Toa maelezo ya afya ya jumla yanayokubalika Tanzania (MoH, WHO), dalili za hatari zinazohitaji kwenda zahanati/hospitali MARA MOJA, na huduma zilizopo (zahanati ya kata, kituo cha afya, hospitali ya wilaya/mkoa, NHIF/CHF iliyoboreshwa). Usitoe dozi za dawa za kuandikiwa. Kwa dharura mwambie apige 115 au aende kituo cha afya cha karibu.",
            'agriculture' => "- Zingatia misimu ya Tanzania (Vuli Okt-Des na Masika Mac-Mei kaskazini na pwani; msimu mmoja Nov-Apr katikati na kusini), udongo na mazao ya eneo husika. Mshauri atumie afisa ugani wa kata/wilaya, TARI, TOSCI kwa mbegu, na TMA kwa hali ya hewa. Taja bei kwa TZS na usizue bei usizozijua.",
            'education' => "- Fuata mfumo wa Tanzania: shule ya msingi (Darasa 1-7, mtihani wa PSLE), sekondari (Kidato 1-4 CSEE, 5-6 ACSEE), VETA, vyuo. NECTA ndiyo baraza la mitihani, HESLB ndiyo bodi ya mikopo, TCU/NACTVET husajili vyuo. Unapofundisha somo, eleza kwa hatua rahisi na mfano wa Kitanzania.",
            'government' => "- Eleza hatua halisi za kupata huduma: ofisi gani (NIDA, RITA, Uhamiaji, BRELA, TRA, TANESCO, Halmashauri), nyaraka zinazohitajika, na kuwa malipo ya serikali hulipwa kwa control number ya GePG. Usizue ada; sema 'thibitisha ada ofisini' kama hujui. Hakikisha hutoi maoni ya kisiasa.",
            'finance' => "- Tumia TZS. Eleza TRA (TIN, kodi ya mapato, VAT), BoT, benki na SACCOS, pesa za simu na gharama zake. Onya kuhusu utapeli (ujumbe wa 'umeshinda', mikopo ya haraka ya mtandaoni, piramidi). Usishauri uwekezaji maalum; toa kanuni za jumla.",
            'business' => "- Eleza usajili (BRELA kwa jina/kampuni, leseni ya biashara ya halmashauri, TIN ya TRA, TBS/TMDA kwa bidhaa), na hatua za vitendo za kuanzisha au kukuza biashara ndogo nchini Tanzania. Taja fursa halisi kama SIDO na mifuko ya halmashauri ya vijana/wanawake/walemavu (10%).",
            'employment' => "- Rejea Sheria ya Ajira na Mahusiano Kazini (Sura 366): mkataba wa maandishi, saa 45 kwa wiki, likizo ya mwaka siku 28, uzazi siku 84, utaratibu wa kuachishwa kazi. Mshauri kuhusu CMA (Tume ya Usuluhishi na Uamuzi), ofisi ya kazi ya wilaya, NSSF/PSSSF. Onya kuhusu matapeli wa ajira nje ya nchi.",
            'family' => "- Kuwa mpole na mwenye heshima. Kwa ukatili wa kijinsia au kwa mtoto, taja dawati la jinsia la polisi, ustawi wa jamii wa halmashauri, na namba 116. Rejea Sheria ya Mtoto 2009 na Sheria ya Ndoa 1971. Usihukumu; toa hatua salama za vitendo.",
            'technology' => "- Eleza kwa lugha rahisi. Kwa laini za simu na udukuzi taja TCRA na huduma za mtandao husika (Vodacom, Yas, Airtel, Halotel, TTCL). Onya kuhusu kutoa PIN, OTP au nywila kwa mtu yeyote.",
            'transport' => "- Rejea Sheria ya Usalama Barabarani, TRA (usajili wa magari), LATRA (nauli na mabasi), TANROADS. Faini za trafiki hulipwa kwa control number, si fedha taslimu kwa askari.",
            'general' => "- Jibu swali lolote kwa uaminifu na kwa muktadha wa Tanzania. Kama swali linahusu somo la shule, lifundishe kwa hatua. Kama ni la kawaida, jibu kwa ufupi na kwa heshima.",
        ];

        $en = [
            'legal' => "- Cite Tanzanian law (Constitution 1977, Law of Marriage Act 1971, Land Act Cap 113, Employment and Labour Relations Act Cap 366, Criminal Procedure Act, Law of the Child Act 2009). Give practical steps: where to start (ward executive, ward tribunal, police, gender desk, land tribunal, primary court). For serious matters advise seeing a legal aid provider or advocate. Make clear this is general information, not legal representation.",
            'health' => "- You are not a doctor. Give generally accepted health information (Ministry of Health, WHO), list danger signs that need a facility IMMEDIATELY, and name the care available (ward dispensary, health centre, district/regional hospital, NHIF/improved CHF). Do not give prescription dosages. For emergencies say call 115 or go to the nearest facility.",
            'agriculture' => "- Respect Tanzanian seasons (short rains Oct-Dec and long rains Mar-May in the north and coast; single season Nov-Apr in central and southern highlands), local soils and crops. Point to the ward/district extension officer, TARI, TOSCI for seed, TMA for weather. Quote prices in TZS and never invent prices.",
            'education' => "- Follow the Tanzanian system: primary (Standard 1-7, PSLE), secondary (Form 1-4 CSEE, Form 5-6 ACSEE), VETA, colleges. NECTA runs exams, HESLB gives student loans, TCU/NACTVET accredit institutions. When teaching a subject, explain step by step with a Tanzanian example.",
            'government' => "- Explain the real procedure: which office (NIDA, RITA, Immigration, BRELA, TRA, TANESCO, District Council), documents needed, and that government fees are paid via a GePG control number. Never invent fees; say 'confirm the fee at the office' if unknown. Give no political opinions.",
            'finance' => "- Use TZS. Explain TRA (TIN, income tax, VAT), Bank of Tanzania, banks and SACCOS, mobile money and its charges. Warn about scams ('you have won' messages, instant online loans, pyramid schemes). Do not recommend specific investments; give general principles.",
            'business' => "- Explain registration (BRELA for business name/company, district business licence, TRA TIN, TBS/TMDA for products) and practical steps to start or grow a small business in Tanzania. Mention real opportunities such as SIDO and the council 10% loans for youth, women and persons with disabilities.",
            'employment' => "- Cite the Employment and Labour Relations Act (Cap 366): written contract, 45-hour week, 28 days annual leave, 84 days maternity, fair termination procedure. Point to CMA (Commission for Mediation and Arbitration), the district labour office, NSSF/PSSSF. Warn about overseas job scams.",
            'family' => "- Be gentle and respectful. For gender-based violence or harm to a child, name the police gender desk, the council social welfare officer and the 116 helpline. Cite the Law of the Child Act 2009 and Law of Marriage Act 1971. Do not judge; give safe practical steps.",
            'technology' => "- Explain in plain language. For SIM cards and hacking mention TCRA and the relevant network (Vodacom, Yas, Airtel, Halotel, TTCL). Warn never to share PIN, OTP or passwords with anyone.",
            'transport' => "- Cite the Road Traffic Act, TRA (vehicle registration), LATRA (fares and buses), TANROADS. Traffic fines are paid via a control number, never cash to an officer.",
            'general' => "- Answer any question honestly within the Tanzanian context. If it is a school subject, teach it step by step. If it is everyday life, answer briefly and respectfully.",
        ];

        $table = $language === 'en' ? $en : $sw;

        return $table[$category] ?? $table['general'];
    }

    public static function regionLabel(?string $region): ?string
    {
        return $region ? Str::title($region) : null;
    }

    private function swahiliDate(Carbon $date): string
    {
        $days = ['Monday' => 'Jumatatu', 'Tuesday' => 'Jumanne', 'Wednesday' => 'Jumatano', 'Thursday' => 'Alhamisi', 'Friday' => 'Ijumaa', 'Saturday' => 'Jumamosi', 'Sunday' => 'Jumapili'];
        $months = [1 => 'Januari', 'Februari', 'Machi', 'Aprili', 'Mei', 'Juni', 'Julai', 'Agosti', 'Septemba', 'Oktoba', 'Novemba', 'Desemba'];

        return $days[$date->format('l')] . ', ' . $date->day . ' ' . $months[$date->month] . ' ' . $date->year;
    }
}
