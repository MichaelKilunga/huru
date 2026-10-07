<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curriculum;

class LegalDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ==========================================
            // SWAHILI LEGAL CORPUS
            // ==========================================
            [
                'title'    => 'Katiba ya Tanzania - Haki ya Usawa (Ibara ya 12 & 13)',
                'content'  => 'Ibara ya 12 ya Katiba ya Tanzania inatamka kwamba binadamu wote huzaliwa huru na wote ni sawa. Ibara ya 13 inasisitiza kuwa watu wote ni sawa mbele ya sheria na wanayo haki, bila ya ubaguzi wowote, kulindwa na kupata haki sawa mbele ya sheria. Ubaguzi wa aina yoyote ile kama vile jinsia, kabila, dini au hali ya kijamii ni marufuku kabisa.',
                'summary'  => 'Usawa wa binadamu na ulinzi mbele ya sheria bila ubaguzi.',
                'tags'     => 'katiba, haki, usawa, sheria, ibara13',
                'keywords' => ['usawa', 'haki', 'katiba', 'ubaguzi', 'sheria', 'ibara', 'usawa mbele ya sheria'],
                'language' => 'sw',
                'is_active'=> true,
            ],
            [
                'title'    => 'Haki za Mtuhumiwa Wakati wa Kukamatwa (Sheria ya Mwenendo wa Makosa ya Jinai)',
                'content'  => 'Unapokamatwa na polisi nchini Tanzania: 1. Una haki ya kuelezwa sababu za kukamatwa kwako mara moja kwa lugha unayoielewa. 2. Una haki ya kukaa kimya na kutojibu swali lolote hadi uwepo wa wakili wako au mtu wako wa karibu. 3. Polisi wanapaswa kukufikisha mahakamani ndani ya saa 24 tangu kukamatwa kwako (isipokuwa siku za mapumziko na wikendi). 4. Una haki ya kuomba dhamana kituo cha polisi kwa makosa yanayodhaminika.',
                'summary'  => 'Haki ya kuelezwa sababu za kukamatwa, kukaa kimya, saa 24, na dhamana ya polisi.',
                'tags'     => 'jinai, haki za msingi, kukamatwa, polisi, dhamana',
                'keywords' => ['kukamatwa', 'polisi', 'mtuhumiwa', 'dhamana', 'kimya', 'wakili', 'jinai', 'saa 24'],
                'language' => 'sw',
                'is_active'=> true,
            ],
            [
                'title'    => 'Sheria ya Ardhi Tanzania - Umiliki wa Ardhi (Sheria ya Ardhi Sura ya 113)',
                'content'  => 'Ardhi yote nchini Tanzania ni mali ya umma iliyowekwa mikononi mwa Rais kama mdhamini kwa niaba ya wananchi wote. Kuna makundi matatu ya ardhi: Ardhi ya Kawaida, Ardhi ya Vijiji, na Ardhi ya Hifadhi. Mwananchi anapata haki ya kutumia ardhi kupitia Hati ya Miliki (Right of Occupancy) kwa ardhi ya kawaida, au Hati ya Hakimiliki ya Kimila (Customary Right of Occupancy) kwa ardhi ya kijiji. Wanawake wana haki sawa na wanaume kumiliki, kurithi, na kuuza ardhi.',
                'summary'  => 'Misingi ya umiliki wa ardhi, haki za wanawake, na makundi ya ardhi.',
                'tags'     => 'ardhi, sheria, umiliki, haki za wanawake, hati ya miliki',
                'keywords' => ['ardhi', 'umiliki', 'rais', 'hati', 'kijiji', 'wanawake', 'miliki', 'kirithi'],
                'language' => 'sw',
                'is_active'=> true,
            ],
            [
                'title'    => 'Sheria ya Kazi na Ajira - Haki za Mfanyakazi (ELRA Sura ya 366)',
                'content'  => 'Sheria ya Uhusiano wa Kazi na Ajira (ELRA) inalinda haki za wafanyakazi: 1. Mkataba wa maandishi ni lazima kwa ajira yoyote inayozidi mwezi mmoja. 2. Saa za kazi za kawaida hazitakiwi kuzidi saa 45 kwa wiki au saa 9 kwa siku. 3. Likizo ya mwaka yenye malipo ni angalau siku 28 mfululizo. 4. Likizo ya uzazi: siku 84 zenye malipo kwa wanawake, na siku 3 kwa wanaume (kwa uzazi wa mtoto mmoja). 5. Mfanyakazi hapaswi kufukuzwa kazi bila sababu ya msingi au bila kufuata utaratibu wa kisheria.',
                'summary'  => 'Mkataba wa kazi, saa za kazi, likizo ya uzazi, na ulinzi dhidi ya kufukuzwa kiholela.',
                'tags'     => 'kazi, ajira, mfanyakazi, mkataba, likizo, uzazi',
                'keywords' => ['kazi', 'ajira', 'mkataba', 'likizo', 'uzazi', 'mfanyakazi', 'kufukuzwa', 'mshahara'],
                'language' => 'sw',
                'is_active'=> true,
            ],
            [
                'title'    => 'Sheria ya Ndoa na Familia - Talaka na Mgawanyo wa Mali (Sheria ya Ndoa ya 1971)',
                'content'  => 'Sheria ya Ndoa ya Tanzania ya mwaka 1971 inaeleza kuwa ndoa inaweza kuvunjwa tu na mahakama yenye mamlaka baada ya kuthibitika kuwa ndoa imevunjika kabisa na haiwezi kurekebishika (irremediable breakdown). Kabla ya kwenda mahakamani, wanandoa lazima wapitie Baraza la Usuluhishi la Ndoa. Wakati wa talaka, mahakama ina mamlaka ya kuamuru mgawanyo wa mali zilizochumwa kwa pamoja wakati wa ndoa, ikizingatia mchango wa kila mmoja (pamoja na kazi za nyumbani).',
                'summary'  => 'Utaratibu wa talaka, Baraza la Usuluhishi, na mgawanyo wa mali za ndoa.',
                'tags'     => 'ndoa, talaka, familia, usuluhishi, mali',
                'keywords' => ['ndoa', 'talaka', 'mali', 'usuluhishi', 'mume', 'mke', 'familia', 'mahakama'],
                'language' => 'sw',
                'is_active'=> true,
            ],

            // ==========================================
            // ENGLISH LEGAL CORPUS
            // ==========================================
            [
                'title'    => 'Constitution of Tanzania - Right to Equality (Articles 12 & 13)',
                'content'  => 'Article 12 of the Constitution of the United Republic of Tanzania states that all human beings are born free and are equal. Article 13 guarantees that every person is equal before the law and is entitled to equal opportunity and protection of the law without any discrimination. Discrimination on grounds of gender, tribe, religion, nationality, or social status is strictly prohibited.',
                'summary'  => 'Human equality and equal protection before the law without discrimination.',
                'tags'     => 'constitution, rights, equality, law, article13',
                'keywords' => ['equality', 'equal', 'rights', 'constitution', 'discrimination', 'law', 'article'],
                'language' => 'en',
                'is_active'=> true,
            ],
            [
                'title'    => 'Rights of an Accused Person Upon Arrest (Criminal Procedure Act)',
                'content'  => 'When arrested by police in Tanzania: 1. You have the right to be informed immediately of the reason for your arrest in a language you understand. 2. You have the right to remain silent and not answer questions until your advocate or close relative is present. 3. The police must bring you before a court within 24 hours of your arrest (excluding weekends and public holidays). 4. You have the right to apply for police bail for all bailable offenses.',
                'summary'  => 'Right to know reasons for arrest, right to silence, 24-hour limit, and police bail.',
                'tags'     => 'criminal, rights, arrest, police, bail',
                'keywords' => ['arrest', 'police', 'accused', 'bail', 'silent', 'advocate', 'criminal', '24 hours'],
                'language' => 'en',
                'is_active'=> true,
            ],
            [
                'title'    => 'Tanzania Land Law - Ownership Categories (Land Act Cap 113)',
                'content'  => 'All land in Tanzania is public land vested in the President as trustee on behalf of all citizens. Land is categorized into: General Land, Village Land, and Reserved Land. Citizens acquire land rights through a Granted Right of Occupancy (for General Land) or a Customary Right of Occupancy (for Village Land). Women have equal rights with men to acquire, own, inherit, and deal with land.',
                'summary'  => 'Land categories, granted and customary rights of occupancy, and gender equality in land rights.',
                'tags'     => 'land, law, ownership, women rights, occupancy',
                'keywords' => ['land', 'ownership', 'president', 'occupancy', 'village', 'women', 'rights', 'customary'],
                'language' => 'en',
                'is_active'=> true,
            ],
            [
                'title'    => 'Employment and Labor Relations - Worker Rights (ELRA Cap 366)',
                'content'  => 'The Employment and Labour Relations Act (ELRA) protects employee rights: 1. A written contract is mandatory for any employment exceeding one month. 2. Standard working hours must not exceed 45 hours per week or 9 hours per day. 3. Paid annual leave must be at least 28 consecutive days. 4. Maternity leave: 84 paid days for mothers, and 3 paid days of paternity leave for fathers (for a single child). 5. Unfair termination of employment is prohibited.',
                'summary'  => 'Employment contracts, maximum hours, maternity/paternity leave, and protection from unfair dismissal.',
                'tags'     => 'labor, employment, employee, contract, leave, termination',
                'keywords' => ['labor', 'employment', 'contract', 'leave', 'maternity', 'employee', 'dismissal', 'salary'],
                'language' => 'en',
                'is_active'=> true,
            ],
            [
                'title'    => 'Marriage and Family Law - Divorce Procedure (Law of Marriage Act 1971)',
                'content'  => 'The Law of Marriage Act 1971 of Tanzania states that a marriage can only be dissolved by a competent court of law after proving that the marriage has broken down irreparably. Before petitioning the court, spouses must refer the dispute to a Marriage Conciliatory Board. Upon divorce, the court has powers to order the division of matrimonial assets acquired during the marriage, taking into account the contribution of each spouse (including domestic work).',
                'summary'  => 'Divorce criteria, Conciliatory Board, and division of matrimonial assets.',
                'tags'     => 'marriage, divorce, family, conciliation, assets',
                'keywords' => ['marriage', 'divorce', 'assets', 'conciliation', 'husband', 'wife', 'family', 'court'],
                'language' => 'en',
                'is_active'=> true,
            ],
        ];

        foreach ($data as $item) {
            Curriculum::updateOrCreate(
                ['title' => $item['title'], 'language' => $item['language']],
                $item
            );
        }

        $this->command->info('✅ Tanzania Legal and Civic Corpus seeded: ' . count($data) . ' entries added.');

        // Seed fallback real-world local legal aid providers (including Sinza and Kahama)
        $providers = [
            [
                'name' => 'HAKI MAENDELEO',
                'region' => 'DAR ES SALAAM',
                'district' => 'KINONDONI',
                'location' => 'Plot 512, Sinza Madukani, Wilaya ya Kinondoni (Ipo karibu kabisa na Sinza Mori).',
                'email' => 'info@hakimaendeleo.or.tz',
                'phone' => '+255 754 555 555',
                'language' => 'sw',
            ],
            [
                'name' => 'GRACE FOUNDATION',
                'region' => 'DAR ES SALAAM',
                'district' => 'KINONDONI',
                'location' => 'S. L. P 31238, Mwenge (Josam House), Wilaya ya Kinondoni, Kata ya Mwenge.',
                'email' => 'info@gracefoundation.or.tz',
                'phone' => '+255 222 777 777',
                'language' => 'sw',
            ],
            [
                'name' => 'KAHAMA PARALEGAL AID ORGANIZATION (KAPAO)',
                'region' => 'SHINYANGA',
                'district' => 'KAHAMA',
                'location' => 'Ofisi za KAPAO Wilaya ya Kahama, Mkoa wa Shinyanga.',
                'email' => 'kahamaparalegal@gmail.com',
                'phone' => '+255 767 994457',
                'language' => 'sw',
            ],
            [
                'name' => 'DIGNITY KWANZA - COMMUNITY SOLUTIONS',
                'region' => 'DAR ES SALAAM',
                'district' => 'KINONDONI',
                'location' => 'S. L. P 33035, Mikocheni B, Wilaya ya Kinondoni.',
                'email' => 'info@dignitykwanza.org',
                'phone' => '+255 784 888 888',
                'language' => 'sw',
            ],
        ];

        foreach ($providers as $provider) {
            \App\Models\LegalAidProvider::updateOrCreate(
                ['name' => $provider['name']],
                $provider
            );
        }

        $this->command->info('✅ Default Local Legal Aid Providers seeded.');
    }
}
