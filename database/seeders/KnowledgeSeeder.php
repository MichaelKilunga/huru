<?php

namespace Database\Seeders;

use App\Models\KnowledgeEntry;
use Illuminate\Database\Seeder;

/**
 * Starter knowledge base: verified-style fact blocks across everyday
 * Tanzanian topics. Admins extend this through the panel or CSV/JSON import.
 */
class KnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            // ---------------------------------------------------------- LEGAL
            [
                'title' => 'Katiba ya Tanzania - Usawa mbele ya sheria (Ibara ya 12 na 13)',
                'category' => 'legal', 'language' => 'sw',
                'content' => 'Ibara ya 12 ya Katiba ya Jamhuri ya Muungano wa Tanzania (1977) inatamka kuwa binadamu wote huzaliwa huru na wote ni sawa. Ibara ya 13 inasisitiza kuwa watu wote ni sawa mbele ya sheria na wana haki ya kulindwa na kupata haki sawa bila ubaguzi wa jinsia, kabila, dini, hali ya kijamii au ulemavu. Mwananchi anayebaguliwa na ofisi ya umma anaweza kulalamika kwa Tume ya Haki za Binadamu na Utawala Bora (CHRAGG) au mahakamani.',
                'keywords' => ['katiba', 'usawa', 'ubaguzi', 'haki za binadamu', 'chragg', 'ibara 13'],
                'source' => 'Katiba ya Jamhuri ya Muungano wa Tanzania, 1977',
            ],
            [
                'title' => 'Constitution of Tanzania - Equality before the law (Articles 12 and 13)',
                'category' => 'legal', 'language' => 'en',
                'content' => 'Article 12 of the Constitution of the United Republic of Tanzania (1977) states that all human beings are born free and equal. Article 13 guarantees equality before the law and equal protection without discrimination on grounds of gender, tribe, religion, social status or disability. A citizen discriminated against by a public office may complain to the Commission for Human Rights and Good Governance (CHRAGG) or to the courts.',
                'keywords' => ['constitution', 'equality', 'discrimination', 'human rights', 'chragg', 'article 13'],
                'source' => 'Constitution of the United Republic of Tanzania, 1977',
            ],
            [
                'title' => 'Haki zako ukikamatwa na polisi',
                'category' => 'legal', 'language' => 'sw',
                'content' => 'Ukikamatwa Tanzania: 1) Una haki ya kuambiwa sababu ya kukamatwa kwako mara moja kwa lugha unayoielewa. 2) Una haki ya kukaa kimya na kutosaini maelezo yoyote mpaka uelewe yaliyomo; unaweza kuomba wakili au ndugu awepo. 3) Polisi wanapaswa kukufikisha mahakamani ndani ya saa 24 (ukiondoa siku za mapumziko) au kukupa dhamana ya polisi kwa makosa yanayodhaminika. 4) Una haki ya kupigiwa simu ndugu au wakili. 5) Kupigwa, kuteswa au kulazimishwa kukiri ni kinyume cha sheria; ripoti kwa Mkuu wa Kituo, Jeshi la Polisi (112), CHRAGG au mtoa msaada wa kisheria. Dhamana ya polisi ni bure; usilipe fedha kwa askari.',
                'keywords' => ['kukamatwa', 'polisi', 'dhamana', 'saa 24', 'kukaa kimya', 'wakili', 'mtuhumiwa', 'kituo cha polisi'],
                'source' => 'Sheria ya Mwenendo wa Makosa ya Jinai, Sura ya 20',
            ],
            [
                'title' => 'Your rights when arrested by the police',
                'category' => 'legal', 'language' => 'en',
                'content' => 'If arrested in Tanzania: 1) You must be told the reason immediately in a language you understand. 2) You may remain silent and should not sign a statement you do not understand; you may ask for an advocate or relative. 3) Police must bring you to court within 24 hours (excluding holidays) or grant police bail for bailable offences. 4) You may call a relative or lawyer. 5) Beating, torture or forced confession is unlawful; report to the Officer Commanding Station, the police (112), CHRAGG or a legal aid provider. Police bail is free; never pay cash to an officer.',
                'keywords' => ['arrest', 'arrested', 'police', 'bail', '24 hours', 'silent', 'advocate', 'suspect', 'police station'],
                'source' => 'Criminal Procedure Act, Cap 20',
            ],
            [
                'title' => 'Umiliki wa ardhi na migogoro ya ardhi',
                'category' => 'legal', 'language' => 'sw',
                'content' => 'Ardhi yote Tanzania ni mali ya umma iliyo chini ya Rais kama mdhamini. Kuna ardhi ya kawaida (General), ardhi ya kijiji (Village) na ardhi ya hifadhi (Reserved). Mwananchi anapata Hati ya Haki Miliki (Granted Right of Occupancy) kwa ardhi ya kawaida kupitia Ofisi ya Ardhi ya Halmashauri/Mkoa, au Hati ya Hakimiliki ya Kimila (CCRO) kwa ardhi ya kijiji kupitia Halmashauri ya Kijiji. Wanawake wana haki sawa na wanaume kumiliki na kurithi ardhi. Migogoro ya ardhi huanzia Baraza la Ardhi la Kijiji, kisha Baraza la Kata, kisha Baraza la Ardhi na Nyumba la Wilaya (DLHT), kisha Mahakama Kuu Kitengo cha Ardhi. Kabla ya kununua ardhi hakiki hati na umiliki Ofisi ya Ardhi na kwa Mtendaji wa Kijiji/Mtaa.',
                'keywords' => ['ardhi', 'hati', 'umiliki', 'ccro', 'baraza la ardhi', 'mgogoro wa ardhi', 'kununua ardhi', 'kiwanja', 'shamba', 'mashamba', 'kudhulumiwa', 'dhuluma', 'kunyang\'anywa', 'mpaka', 'mipaka', 'kuvamiwa', 'eneo', 'mali', 'hatimiliki', 'kurithi ardhi', 'wakulima'],
                'source' => 'Sheria ya Ardhi Sura 113; Sheria ya Ardhi ya Vijiji Sura 114; Sheria ya Mahakama za Ardhi Sura 216',
            ],
            [
                'title' => 'Land ownership and land disputes',
                'category' => 'legal', 'language' => 'en',
                'content' => 'All land in Tanzania is public land vested in the President as trustee. Categories: General land, Village land and Reserved land. Citizens obtain a Granted Right of Occupancy (General land) through the District/Regional Land Office, or a Certificate of Customary Right of Occupancy (CCRO) for village land through the Village Council. Women have equal rights to own and inherit land. Disputes go from the Village Land Council to the Ward Tribunal, then the District Land and Housing Tribunal (DLHT), then the High Court Land Division. Before buying land, verify the title at the Land Office and with the Village/Mtaa Executive Officer.',
                'keywords' => ['land', 'title deed', 'ownership', 'ccro', 'land tribunal', 'land dispute', 'buying land', 'plot', 'farm', 'grabbed', 'grabbing', 'boundary', 'encroachment', 'trespass', 'inherit land', 'village land'],
                'source' => 'Land Act Cap 113; Village Land Act Cap 114; Land Disputes Courts Act Cap 216',
            ],
            [
                'title' => 'Ndoa, talaka na mgawanyo wa mali',
                'category' => 'legal', 'language' => 'sw',
                'content' => 'Sheria ya Ndoa ya 1971 inatambua ndoa za kiserikali, za kidini na za kimila; zote zinapaswa kusajiliwa RITA/ofisi ya msajili wa ndoa. Mahakama zimetamka kuwa umri wa chini wa kuoa au kuolewa ni miaka 18 kwa wote. Talaka hutolewa na mahakama tu baada ya kuthibitisha ndoa imevunjika kabisa; kabla ya kwenda mahakamani lazima kupitia Baraza la Usuluhishi la Ndoa (ngazi ya kata au la kidini). Wakati wa talaka mahakama hugawa mali zilizochumwa pamoja kwa kuzingatia mchango wa kila mmoja, ikiwemo kazi za nyumbani. Matunzo ya watoto hupewa kipaumbele kwa maslahi ya mtoto.',
                'keywords' => ['ndoa', 'talaka', 'mali ya ndoa', 'usuluhishi', 'mke', 'mume', 'matunzo', 'kusajili ndoa', 'miaka 18'],
                'source' => 'Sheria ya Ndoa, 1971 (Sura 29)',
            ],
            [
                'title' => 'Marriage, divorce and division of property',
                'category' => 'legal', 'language' => 'en',
                'content' => 'The Law of Marriage Act 1971 recognises civil, religious and customary marriages; all should be registered with RITA or the marriage registrar. Courts have held that the minimum age of marriage is 18 for everyone. Divorce is granted only by a court after proof of irreparable breakdown, and only after referral to a Marriage Conciliation Board (ward or religious). On divorce the court divides assets acquired jointly, counting each spouse\'s contribution including housework. Custody decisions follow the best interest of the child.',
                'keywords' => ['marriage', 'divorce', 'matrimonial property', 'conciliation', 'custody', 'register marriage', 'age 18'],
                'source' => 'Law of Marriage Act, 1971 (Cap 29)',
            ],
            [
                'title' => 'Mirathi na wosia',
                'category' => 'legal', 'language' => 'sw',
                'content' => 'Mtu anaweza kuandika wosia (will) akiwa na akili timamu; wosia wa maandishi usainiwe mbele ya mashahidi wawili wasio warithi. Mtu akifa bila wosia mirathi hugawanywa kwa sheria inayomhusu: sheria ya kimila, ya Kiislamu, au Sheria ya Mirathi ya India (kwa wengine). Ndugu hukutana kuchagua msimamizi wa mirathi, kisha msimamizi huomba barua ya usimamizi Mahakama ya Mwanzo (mirathi ndogo) au Mahakama ya Wilaya/Mahakama Kuu (mirathi kubwa). Cheti cha kifo kutoka RITA ni lazima. Watoto, wajane na wagane wana haki ya kurithi; kunyang\'anywa mali ya marehemu bila utaratibu ni kosa. Msaada: mtoa msaada wa kisheria, TAWLA, WLAC, LHRC.',
                'keywords' => ['mirathi', 'wosia', 'urithi', 'msimamizi wa mirathi', 'marehemu', 'mjane', 'cheti cha kifo', 'warithi'],
                'source' => 'Sheria ya Usimamizi wa Mirathi Sura 352; Sheria ya Mahakama za Mahakimu Sura 11',
            ],
            [
                'title' => 'Haki za mpangaji na mwenye nyumba',
                'category' => 'legal', 'language' => 'sw',
                'content' => 'Mkataba wa pango uwe wa maandishi na uonyeshe kodi, muda na masharti; kwa makazi mwenye nyumba haruhusiwi kudai kodi ya zaidi ya miezi 12 mapema (kwa malipo ya kila mwezi, kodi ya awali hupangwa kwa makubaliano) na lazima atoe risiti. Mwenye nyumba hawezi kumfukuza mpangaji kwa nguvu, kufunga milango au kukata maji/umeme bila amri ya mahakama; notisi ya maandishi inahitajika kwa mujibu wa mkataba. Migogoro ya pango husikilizwa na Baraza la Ardhi na Nyumba la Wilaya (DLHT). Mpangaji anawajibika kulipa kodi kwa wakati na kutunza nyumba.',
                'keywords' => ['pango', 'mpangaji', 'mwenye nyumba', 'kodi ya nyumba', 'kufukuzwa', 'notisi', 'dlht', 'nyumba'],
                'source' => 'Sheria ya Ardhi Sura 113 Sehemu ya Pango; Sheria ya Mahakama za Ardhi Sura 216',
            ],

            // ---------------------------------------------------------- EMPLOYMENT
            [
                'title' => 'Haki za mfanyakazi (Sheria ya Ajira na Mahusiano Kazini)',
                'category' => 'employment', 'language' => 'sw',
                'content' => 'Sheria ya Ajira na Mahusiano Kazini (ELRA, Sura 366): 1) Mkataba wa maandishi ni lazima kwa ajira inayozidi miezi 6; mwajiri lazima atoe nakala. 2) Saa za kazi hazizidi 45 kwa wiki au 9 kwa siku; saa za ziada hulipwa mara 1.5 ya kiwango cha kawaida. 3) Likizo ya mwaka ni siku 28 mfululizo zenye malipo. 4) Likizo ya uzazi: siku 84 zenye malipo (siku 100 kwa mapacha) kwa mama, siku 3 kwa baba. 5) Likizo ya ugonjwa hadi siku 126 kwa mzunguko wa miezi 36. 6) Kuachishwa kazi lazima kuwe na sababu halali na utaratibu wa haki (kusikilizwa). Mgogoro wa kuachishwa kazi isivyo haki huwasilishwa CMA ndani ya siku 30; migogoro mingine ndani ya siku 60. Kima cha chini cha mshahara hutofautiana kwa sekta (angalia tangazo la Serikali la hivi karibuni).',
                'keywords' => ['ajira', 'mfanyakazi', 'mkataba wa kazi', 'likizo', 'uzazi', 'kufukuzwa kazi', 'cma', 'mshahara', 'saa za kazi', 'overtime'],
                'source' => 'Sheria ya Ajira na Mahusiano Kazini, Sura 366',
            ],
            [
                'title' => 'Worker rights (Employment and Labour Relations Act)',
                'category' => 'employment', 'language' => 'en',
                'content' => 'Employment and Labour Relations Act (Cap 366): 1) A written contract is required for employment over 6 months; the employer must give the worker a copy. 2) Working hours max 45 per week or 9 per day; overtime is paid at 1.5 times. 3) Annual leave is 28 consecutive paid days. 4) Maternity leave 84 paid days (100 for multiple births), paternity leave 3 days. 5) Sick leave up to 126 days per 36-month cycle. 6) Termination needs a valid reason and a fair procedure (a hearing). Unfair termination disputes go to the CMA within 30 days; other disputes within 60 days. Minimum wages differ by sector (check the latest Government Notice).',
                'keywords' => ['employment', 'employee', 'contract', 'leave', 'maternity', 'dismissal', 'termination', 'cma', 'wage', 'working hours', 'overtime'],
                'source' => 'Employment and Labour Relations Act, Cap 366',
            ],
            [
                'title' => 'Kutafuta kazi na kuepuka matapeli wa ajira',
                'category' => 'employment', 'language' => 'sw',
                'content' => 'Nafasi halali za kazi za umma hutangazwa na Sekretarieti ya Ajira katika Utumishi wa Umma (ajira.go.tz) na tovuti za taasisi husika; hakuna malipo ya kuomba kazi serikalini. Tahadhari: mtu anayekuomba fedha "ya usaili", "ya mafunzo" au "ya kibali" kabla ya kuajiriwa ni tapeli. Kwa ajira nje ya nchi tumia mawakala waliosajiliwa na Wizara ya Kazi pekee; hakiki mkataba na usikabidhi pasipoti yako kwa wakala. CV nzuri: taarifa za mawasiliano, elimu, uzoefu, ujuzi, wadhamini wawili; iwe kurasa 1-2.',
                'keywords' => ['kazi', 'nafasi za kazi', 'ajira', 'cv', 'usaili', 'tapeli', 'ajira nje ya nchi', 'wakala'],
                'source' => 'Sekretarieti ya Ajira; Wizara ya Kazi',
            ],

            // ---------------------------------------------------------- HEALTH
            [
                'title' => 'Malaria - dalili, matibabu na kinga',
                'category' => 'health', 'language' => 'sw',
                'content' => 'Dalili za malaria: homa, kutetemeka, maumivu ya kichwa na viungo, kutapika, uchovu. Mtu mwenye dalili apimwe kwa kipimo cha haraka (mRDT) au darubini kwenye zahanati/duka la dawa lililosajiliwa; asinywe dawa bila kipimo. Matibabu ya kwanza Tanzania ni ALu (Artemether-Lumefantrine) inayotolewa na mtaalamu wa afya. Dalili za hatari zinazohitaji hospitali MARA MOJA: kupoteza fahamu, degedege, kushindwa kunywa au kunyonya, kupumua kwa shida, manjano, mkojo mweusi, hasa kwa watoto chini ya miaka 5 na wajawazito. Kinga: lala ndani ya chandarua chenye dawa kila usiku, ondoa maji yaliyotuama, wajawazito wachukue dawa ya kinga (IPTp) kliniki.',
                'keywords' => ['malaria', 'homa', 'chandarua', 'mrdt', 'alu', 'degedege', 'mbu', 'kutetemeka'],
                'source' => 'Mpango wa Taifa wa Kudhibiti Malaria (NMCP), Wizara ya Afya',
            ],
            [
                'title' => 'Malaria - symptoms, treatment and prevention',
                'category' => 'health', 'language' => 'en',
                'content' => 'Malaria symptoms: fever, chills, headache, body pain, vomiting, tiredness. Anyone with symptoms should be tested (mRDT or microscopy) at a dispensary or accredited drug shop before taking medicine. First-line treatment in Tanzania is ALu (Artemether-Lumefantrine) given by a health worker. Danger signs needing a hospital IMMEDIATELY: loss of consciousness, convulsions, inability to drink or breastfeed, difficulty breathing, yellow eyes, dark urine, especially in children under 5 and pregnant women. Prevention: sleep under a treated net every night, remove stagnant water, pregnant women take preventive doses (IPTp) at the clinic.',
                'keywords' => ['malaria', 'fever', 'mosquito net', 'mrdt', 'alu', 'convulsions', 'mosquito'],
                'source' => 'National Malaria Control Programme, Ministry of Health',
            ],
            [
                'title' => 'Bima ya afya: NHIF na CHF iliyoboreshwa (iCHF)',
                'category' => 'health', 'language' => 'sw',
                'content' => 'NHIF (Mfuko wa Taifa wa Bima ya Afya) ni bima ya afya ya umma: watumishi wa umma hukatwa moja kwa moja, na wananchi wengine wanaweza kujiunga kwa hiari kupitia vifurushi vya NHIF (ofisi za NHIF za mkoa/wilaya au mawakala). CHF iliyoboreshwa (iCHF) ni bima ya jamii ya gharama nafuu kwa kaya inayosajiliwa kupitia Mtendaji wa Kata au afisa wa iCHF wa halmashauri; hutumika vituo vya afya vya umma vya wilaya/mkoa. Tanzania inatekeleza Sheria ya Bima ya Afya kwa Wote (2023) kwa awamu. Huduma za bure kwa wote: huduma za mama mjamzito na watoto chini ya miaka 5 kwenye vituo vya umma, chanjo, matibabu ya kifua kikuu na ARV.',
                'keywords' => ['nhif', 'chf', 'ichf', 'bima ya afya', 'kadi ya bima', 'matibabu', 'gharama za matibabu', 'bima kwa wote'],
                'source' => 'NHIF; Wizara ya Afya; Sheria ya Bima ya Afya kwa Wote 2023',
            ],
            [
                'title' => 'Kliniki ya mama mjamzito na chanjo za mtoto',
                'category' => 'health', 'language' => 'sw',
                'content' => 'Mama mjamzito aanze kliniki (RCH) mapema kabla ya miezi 3 na ahudhurie angalau mara 8 kwa mujibu wa mwongozo mpya; kliniki hupima damu, presha, UKIMWI, kaswende, hutoa dawa za kinga ya malaria (IPTp), vidonge vya damu (FeFo), chandarua na kadi ya kliniki. Jifungulia kituo cha afya; huduma ya mama na mtoto chini ya miaka 5 ni bure vituo vya umma. Ratiba ya chanjo ya mtoto Tanzania: kuzaliwa BCG na OPV0; wiki 6, 10, 14 Penta (DTP-HepB-Hib), OPV, PCV, Rota; miezi 9 Surua-Rubella (MR) ya kwanza; miezi 18 MR ya pili. Wasichana wa miaka 9-14 hupata chanjo ya HPV (saratani ya mlango wa kizazi).',
                'keywords' => ['mjamzito', 'kliniki', 'chanjo', 'mtoto', 'kujifungua', 'rch', 'surua', 'hpv', 'penta', 'bcg'],
                'source' => 'Wizara ya Afya - Mpango wa Chanjo (IVD)',
            ],
            [
                'title' => 'Kipindupindu na magonjwa ya kuhara: kinga na hatua za haraka',
                'category' => 'health', 'language' => 'sw',
                'content' => 'Kipindupindu huambukizwa kwa maji au chakula kilichochafuliwa na kinyesi. Dalili: kuhara maji mengi kama maji ya mchele, kutapika, kupungukiwa maji haraka. Hatua za haraka: anza kunywa ORS (dawa ya kuongeza maji mwilini) au maji ya chumvi na sukari mara moja na uende kituo cha afya haraka; watoto wapewe pia zinki. Kinga: nawa mikono kwa sabuni, chemsha au tia dawa (Waterguard) maji ya kunywa, tumia choo, pasha moto chakula, osha matunda. Ripoti mlipuko kwa afisa afya wa kata.',
                'keywords' => ['kipindupindu', 'kuhara', 'ors', 'maji safi', 'choo', 'kunawa mikono', 'mlipuko', 'zinki'],
                'source' => 'Wizara ya Afya - Mwongozo wa Kudhibiti Kipindupindu',
            ],

            // ---------------------------------------------------------- AGRICULTURE
            [
                'title' => 'Misimu ya kilimo Tanzania na kalenda ya kupanda',
                'category' => 'agriculture', 'language' => 'sw',
                'content' => 'Tanzania ina mifumo miwili ya mvua. Maeneo ya kaskazini na pwani (Arusha, Kilimanjaro, Tanga, Dar es Salaam, Pwani, Mwanza, Kagera, Mara) yana misimu miwili: Vuli (Oktoba-Desemba) na Masika (Machi-Mei). Maeneo ya katikati, magharibi na nyanda za juu kusini (Dodoma, Singida, Tabora, Mbeya, Iringa, Njombe, Ruvuma, Songwe, Rukwa, Katavi, Morogoro kusini) yana msimu mmoja mrefu (Novemba/Desemba-Aprili). Panda mahindi mwanzoni mwa mvua za uhakika baada ya utabiri wa TMA; tumia mbegu zilizothibitishwa na TOSCI zenye muda wa kukomaa unaolingana na msimu wa eneo lako. Kwa ushauri wa eneo lako muone afisa ugani wa kata.',
                'keywords' => ['msimu', 'vuli', 'masika', 'mvua', 'kupanda', 'kalenda ya kilimo', 'mahindi', 'tma', 'afisa ugani'],
                'source' => 'Mamlaka ya Hali ya Hewa Tanzania (TMA); Wizara ya Kilimo',
            ],
            [
                'title' => 'Farming seasons in Tanzania and planting calendar',
                'category' => 'agriculture', 'language' => 'en',
                'content' => 'Tanzania has two rainfall regimes. The north and coast (Arusha, Kilimanjaro, Tanga, Dar es Salaam, Pwani, Mwanza, Kagera, Mara) have two seasons: short rains "Vuli" (October-December) and long rains "Masika" (March-May). Central, western and southern highland regions (Dodoma, Singida, Tabora, Mbeya, Iringa, Njombe, Ruvuma, Songwe, Rukwa, Katavi, southern Morogoro) have one long season (November/December-April). Plant maize at the start of reliable rains following TMA forecasts; use TOSCI-certified seed with a maturity period matching your area. Consult the ward extension officer for local advice.',
                'keywords' => ['season', 'rains', 'planting', 'calendar', 'maize', 'tma', 'extension officer', 'rainfall'],
                'source' => 'Tanzania Meteorological Authority (TMA); Ministry of Agriculture',
            ],
            [
                'title' => 'Viwavijeshi vamizi (fall armyworm) kwenye mahindi',
                'category' => 'agriculture', 'language' => 'sw',
                'content' => 'Viwavijeshi vamizi hushambulia mahindi hasa wiki 2-6 baada ya kuota; dalili ni majani yaliyotobolewa na kinyesi kama unga wa mbao kwenye kishungi. Kagua shamba mara 2 kwa wiki asubuhi; ukikuta mimea zaidi ya 20% imeshambuliwa chukua hatua. Hatua: ondoa na uue viwavi kwa mkono kwa shamba dogo, weka mchanga/majivu kwenye kishungi, panda mapema na kwa pamoja na majirani, tumia viuatilifu vilivyosajiliwa na TPHPA (uliza afisa ugani jina sahihi na kipimo), pulizia alasiri/jioni ukielekeza kwenye kishungi. Vaa kinga unapotumia dawa. Panda mazao mchanganyiko (mahindi na maharage/dengu) kupunguza mashambulizi.',
                'keywords' => ['viwavijeshi', 'mahindi', 'wadudu', 'dawa ya wadudu', 'kiuatilifu', 'shamba', 'armyworm'],
                'source' => 'TARI; Wizara ya Kilimo - Mwongozo wa Viwavijeshi',
            ],
            [
                'title' => 'Ufugaji wa kuku: chanjo ya mdondo (Newcastle) na matunzo',
                'category' => 'agriculture', 'language' => 'sw',
                'content' => 'Mdondo (Newcastle) ndio ugonjwa unaoua kuku wengi zaidi vijijini; dalili: kuhara kijani, kupinda shingo, kupooza, vifo vya ghafla. Kinga pekee ni chanjo (I-2 au LaSota) kila baada ya miezi 3-4, mara nyingi kwa kudondosha jichoni au kunywesha; chanjo hupatikana kwa afisa mifugo wa kata, maduka ya pembejeo za mifugo au TVLA. Chanja kuku wote kwa pamoja na majirani. Matunzo: banda lenye hewa safi na likavu, maji safi kila siku, chakula chenye uwiano (mahindi, pumba, mashudu, dagaa, madini), weka vifaranga mbali na kuku wakubwa, dhibiti viroboto na minyoo kwa ushauri wa afisa mifugo. Usile wala kuuza kuku waliokufa kwa ugonjwa.',
                'keywords' => ['kuku', 'mdondo', 'newcastle', 'chanjo ya kuku', 'ufugaji', 'vifaranga', 'banda', 'afisa mifugo'],
                'source' => 'Wizara ya Mifugo na Uvuvi; TVLA',
            ],

            // ---------------------------------------------------------- EDUCATION
            [
                'title' => 'Mfumo wa elimu Tanzania na mitihani ya NECTA',
                'category' => 'education', 'language' => 'sw',
                'content' => 'Mfumo wa elimu: elimu ya awali (mwaka 1), msingi Darasa la 1-7 (mtihani wa Darasa la 4 wa upimaji na Mtihani wa Kumaliza Elimu ya Msingi PSLE Darasa la 7), sekondari ya kawaida Kidato cha 1-4 (FTNA Kidato cha 2, CSEE Kidato cha 4), sekondari ya juu Kidato cha 5-6 (ACSEE), kisha vyuo vya ufundi (VETA, NACTVET) au vyuo vikuu (TCU). Elimu ya msingi hadi Kidato cha 4 shule za umma ni bila ada (Sera ya Elimu bila Malipo). NECTA huendesha mitihani ya taifa na kutangaza matokeo kwenye necta.go.tz (pia kwa SMS zinapotolewa). Madaraja ya CSEE: Daraja la I (pointi 7-17) hadi IV; Daraja 0 ni kufeli. Mitaala mipya ya 2023 inaongeza masomo ya ufundi (amali) sekondari.',
                'keywords' => ['necta', 'matokeo', 'kidato', 'darasa la saba', 'psle', 'csee', 'acsee', 'shule', 'elimu bila malipo', 'mtihani', 'daraja'],
                'source' => 'NECTA; Wizara ya Elimu, Sayansi na Teknolojia',
            ],
            [
                'title' => 'Tanzanian education system and NECTA examinations',
                'category' => 'education', 'language' => 'en',
                'content' => 'Structure: pre-primary (1 year), primary Standard 1-7 (Standard 4 assessment and the Primary School Leaving Examination PSLE in Standard 7), ordinary secondary Form 1-4 (FTNA in Form 2, CSEE in Form 4), advanced secondary Form 5-6 (ACSEE), then vocational colleges (VETA, NACTVET) or universities (TCU). Public primary to Form 4 is fee-free. NECTA runs national exams and publishes results at necta.go.tz. CSEE divisions: Division I (points 7-17) to IV; Division 0 is a fail. The 2023 curriculum adds vocational streams in secondary school.',
                'keywords' => ['necta', 'results', 'form four', 'form six', 'standard seven', 'psle', 'csee', 'acsee', 'school', 'exam', 'division'],
                'source' => 'NECTA; Ministry of Education, Science and Technology',
            ],
            [
                'title' => 'Mikopo ya elimu ya juu (HESLB) na kujiunga na chuo',
                'category' => 'education', 'language' => 'sw',
                'content' => 'Bodi ya Mikopo ya Wanafunzi wa Elimu ya Juu (HESLB) hutoa mikopo kwa Watanzania wanaojiunga na vyuo vilivyosajiliwa (shahada na baadhi ya stashahada). Maombi hufanywa mtandaoni kupitia mfumo wa OLAMS (heslb.go.tz) baada ya kupata udahili, kwa kawaida Julai-Agosti kila mwaka; nyaraka: cheti cha kuzaliwa, vyeti vya Kidato cha 4 na 6, kitambulisho cha NIDA, taarifa za wazazi/walezi (vyeti vya kifo kama wamefariki), barua ya mtendaji. Mkopo hurejeshwa baada ya kuanza kazi kwa kukatwa mshahara. Udahili wa vyuo vikuu hufanywa kupitia vyuo vyenyewe na kuratibiwa na TCU; vyuo vya ufundi na NACTVET. Vigezo vya chini: kwa shahada, pointi kutoka ACSEE au stashahada.',
                'keywords' => ['heslb', 'mkopo wa elimu', 'bodi ya mikopo', 'olams', 'chuo kikuu', 'udahili', 'tcu', 'nactvet', 'ada ya chuo'],
                'source' => 'HESLB; TCU',
            ],

            // ---------------------------------------------------------- GOVERNMENT
            [
                'title' => 'Kitambulisho cha Taifa (NIDA): jinsi ya kujisajili',
                'category' => 'government', 'language' => 'sw',
                'content' => 'Mamlaka ya Vitambulisho vya Taifa (NIDA) husajili Watanzania wenye umri wa miaka 18 na zaidi (na watoto kwa namba ya NIN). Hatua: 1) Chukua fomu ofisi ya mtendaji wa kata/mtaa au ofisi ya NIDA ya wilaya, au anza mtandaoni nida.go.tz. 2) Nyaraka: cheti cha kuzaliwa au hati ya kiapo, cheti cha kuzaliwa/kitambulisho cha mzazi mmoja au barua ya mtendaji, picha, na uthibitisho wa makazi. 3) Alama za vidole na picha huchukuliwa ofisi ya NIDA. 4) Namba ya utambulisho (NIN) hutolewa kwanza, kadi hufuata. Usajili ni bure. NIN inahitajika kwa laini ya simu, TIN, akaunti ya benki, HESLB na huduma nyingi za serikali. Kuhakiki hali ya maombi: nida.go.tz au *152*00#.',
                'keywords' => ['nida', 'kitambulisho cha taifa', 'nin', 'kujisajili', 'kitambulisho', 'alama za vidole', 'cheti cha kuzaliwa'],
                'source' => 'NIDA - nida.go.tz',
            ],
            [
                'title' => 'National ID (NIDA): how to register',
                'category' => 'government', 'language' => 'en',
                'content' => 'The National Identification Authority (NIDA) registers Tanzanians aged 18 and above (children receive a NIN). Steps: 1) Get the form from the ward/mtaa executive office or district NIDA office, or start online at nida.go.tz. 2) Documents: birth certificate or affidavit, a parent\'s birth certificate/ID or a letter from the executive officer, photo, proof of residence. 3) Fingerprints and photo are captured at the NIDA office. 4) The NIN is issued first, the card later. Registration is free. The NIN is required for SIM cards, TIN, bank accounts, HESLB loans and most government services. Check status at nida.go.tz or *152*00#.',
                'keywords' => ['nida', 'national id', 'nin', 'register', 'identity card', 'fingerprints', 'birth certificate'],
                'source' => 'NIDA - nida.go.tz',
            ],
            [
                'title' => 'Cheti cha kuzaliwa na cheti cha kifo (RITA)',
                'category' => 'government', 'language' => 'sw',
                'content' => 'Wakala wa Usajili, Ufilisi na Udhamini (RITA) husajili vizazi na vifo. Usajili wa mtoto ndani ya siku 90 tangu kuzaliwa ni bure kwa cheti cha kwanza; baada ya hapo kuna ada ya ucheleweshaji. Mahali: ofisi ya msajili wa wilaya (RITA), ofisi ya mtendaji wa kata, au hospitali/zahanati zinazosajili moja kwa moja (mfumo wa usajili wa watoto chini ya miaka 5 kwa simu). Nyaraka: notisi ya kuzaliwa kutoka kituo cha afya au barua ya mtendaji, vitambulisho vya wazazi. Cheti cha kifo: notisi ya kifo kutoka hospitali/mtendaji, kitambulisho cha marehemu, ndani ya siku 30. Malipo ya RITA hufanywa kwa control number ya GePG. Tovuti: rita.go.tz, huduma mtandaoni kupitia efms.rita.go.tz.',
                'keywords' => ['rita', 'cheti cha kuzaliwa', 'cheti cha kifo', 'usajili wa kizazi', 'mtoto mchanga', 'notisi ya kuzaliwa'],
                'source' => 'RITA - rita.go.tz',
            ],
            [
                'title' => 'Pasipoti ya Tanzania (e-Passport)',
                'category' => 'government', 'language' => 'sw',
                'content' => 'Pasipoti ya kielektroniki hutolewa na Idara ya Uhamiaji. Hatua: 1) Jaza maombi mtandaoni eservices.immigration.go.tz, pakia nyaraka: kitambulisho cha NIDA (NIN), cheti cha kuzaliwa, picha, na kwa watoto vitambulisho vya wazazi na idhini. 2) Lipa ada kwa control number (GePG). 3) Fika ofisi ya uhamiaji uliyochagua kwa alama za vidole, picha na mahojiano. 4) Pasipoti ya kawaida huwa tayari ndani ya siku chache hadi wiki kadhaa. Ada ya pasipoti ya kawaida ni TZS 150,000 (thibitisha kiwango cha sasa kwenye tovuti). Pasipoti ya Afrika Mashariki ya Tanzania inatumika kusafiri nchi zote.',
                'keywords' => ['pasipoti', 'passport', 'uhamiaji', 'kusafiri nje', 'e-passport', 'immigration'],
                'source' => 'Idara ya Uhamiaji - immigration.go.tz',
            ],
            [
                'title' => 'Namba ya mlipakodi (TIN) na kodi za TRA kwa wafanyabiashara wadogo',
                'category' => 'finance', 'language' => 'sw',
                'content' => 'TIN (Taxpayer Identification Number) hutolewa bure na Mamlaka ya Mapato Tanzania (TRA) mtandaoni (tra.go.tz, huduma za mtandaoni) au ofisi ya TRA ya wilaya; unahitaji NIN ya NIDA. TIN inahitajika kwa biashara, leseni, ajira rasmi, kununua gari au kiwanja. Wafanyabiashara wadogo wasio na hesabu za ukaguzi hulipa kodi ya makisio (presumptive tax) kulingana na mauzo ya mwaka; mauzo chini ya TZS milioni 4 kwa mwaka hayalipi kodi ya mapato ya makisio (lakini usajili wa TIN na leseni unahitajika). VAT husajiliwa kwa mauzo ya zaidi ya TZS milioni 200 kwa mwaka. Dai risiti ya EFD kwa kila manunuzi. Malipo yote ya TRA ni kwa control number; hakuna malipo kwa afisa. Huduma kwa wateja TRA: 0800 750 075 / 0800 780 078 (bure).',
                'keywords' => ['tin', 'tra', 'kodi', 'kodi ya mapato', 'vat', 'efd', 'risiti', 'mlipakodi', 'makisio', 'biashara ndogo'],
                'source' => 'TRA - tra.go.tz',
            ],
            [
                'title' => 'Malipo ya serikali kwa control number (GePG)',
                'category' => 'government', 'language' => 'sw',
                'content' => 'Malipo yote ya serikali (ada, faini, leseni, kodi, huduma za NIDA/RITA/Uhamiaji, ada za shule za umma) hufanywa kupitia Mfumo wa Malipo ya Serikali (GePG). Ofisi husika hukupa namba ya kumbukumbu ya malipo (control number) yenye tarakimu 12 inayoanza na 99. Lipa kwa pesa za simu (M-Pesa, Mixx by Yas, Airtel Money, HaloPesa: chagua "Lipa kwa Serikali/GePG"), benki, au wakala wa benki. Hifadhi ujumbe wa uthibitisho. Usilipe fedha taslimu kwa afisa bila control number na risiti rasmi. Kwa wanafunzi wa shule za umma, ada za bweni na michango halali hulipwa kwa control number ya shule.',
                'keywords' => ['gepg', 'control number', 'malipo ya serikali', 'namba ya kumbukumbu', 'kulipa faini', 'ada', 'kulipa serikali'],
                'source' => 'Wizara ya Fedha - GePG',
            ],
            [
                'title' => 'Umeme wa TANESCO na LUKU',
                'category' => 'government', 'language' => 'sw',
                'content' => 'TANESCO ndiyo kampuni ya umeme Tanzania Bara; kuunganishwa umeme omba ofisi ya TANESCO ya wilaya au mtandaoni (ukiwa na NIN, hati/barua ya mtendaji ya nyumba na ramani ya eneo), lipa ada ya kuunganisha kwa control number. Vijijini kuunganishwa kwa bei nafuu kupitia REA. Umeme wa kulipia kabla (LUKU) hununuliwa kwa pesa za simu, benki au mawakala kwa namba ya mita; weka tokeni ya tarakimu 20 kwenye mita. Kwa hitilafu au umeme kukatika ripoti kwa huduma kwa wateja TANESCO (ofisi ya karibu au namba iliyo kwenye tovuti tanesco.co.tz). Usijiunganishe umeme kinyume cha sheria; ni hatari na ni kosa la jinai.',
                'keywords' => ['tanesco', 'luku', 'umeme', 'kuunganisha umeme', 'mita', 'tokeni', 'rea', 'umeme umekatika'],
                'source' => 'TANESCO - tanesco.co.tz',
            ],

            // ---------------------------------------------------------- FINANCE / BUSINESS
            [
                'title' => 'Utapeli wa pesa za simu na jinsi ya kujilinda',
                'category' => 'finance', 'language' => 'sw',
                'content' => 'Mbinu za kawaida za utapeli: ujumbe au simu ya "umeshinda zawadi", "nimetuma pesa kwa makosa nirudishie", "akaunti yako itafungwa thibitisha PIN", mikopo ya haraka ya mtandaoni inayotaka ada kwanza, na matangazo ya uwekezaji wa faida kubwa haraka (piramidi). Kanuni: 1) Usimpe mtu yeyote PIN, OTP au nywila, hata akijitambulisha kuwa wa benki/kampuni ya simu. 2) Hakiki salio lako kwa menyu rasmi kabla ya "kurudisha" pesa. 3) Mikopo halali hutoka benki, SACCOS au watoa huduma waliosajiliwa na BoT; hawatozi ada kabla ya kukopesha. 4) Ukitapeliwa ripoti mara moja kwa kampuni ya simu (kusimamisha muamala) na polisi (Kitengo cha Makosa ya Mtandao), hifadhi ujumbe na namba. Kupoteza simu: zuia laini na akaunti ya pesa mara moja kwa huduma kwa wateja.',
                'keywords' => ['utapeli', 'tapeli', 'm-pesa', 'pesa za simu', 'pin', 'otp', 'umeshinda', 'mkopo wa haraka', 'piramidi', 'kutapeliwa'],
                'source' => 'BoT; TCRA; Jeshi la Polisi',
            ],
            [
                'title' => 'Mobile money scams and how to stay safe',
                'category' => 'finance', 'language' => 'en',
                'content' => 'Common scams: "you have won a prize" messages or calls, "I sent money by mistake, please return it", "your account will be closed, confirm your PIN", instant online loans that ask for a fee first, and get-rich-quick investment schemes (pyramids). Rules: 1) Never share your PIN, OTP or password with anyone, even someone claiming to be from a bank or telecom. 2) Check your balance in the official menu before "returning" money. 3) Legitimate loans come from banks, SACCOS or BoT-licensed lenders; they never charge a fee before lending. 4) If scammed, report immediately to the mobile operator (to freeze the transaction) and the police cybercrime unit; keep the messages and numbers. Lost phone: block the SIM and money account at once via customer care.',
                'keywords' => ['scam', 'fraud', 'mobile money', 'pin', 'otp', 'won', 'instant loan', 'pyramid', 'lost phone'],
                'source' => 'BoT; TCRA; Tanzania Police',
            ],
            [
                'title' => 'Kuanzisha na kurasimisha biashara ndogo Tanzania',
                'category' => 'business', 'language' => 'sw',
                'content' => 'Hatua za kurasimisha: 1) Pata TIN kutoka TRA (bure). 2) Sajili jina la biashara au kampuni BRELA kupitia mfumo wa ORS (ors.brela.go.tz); jina la biashara ni rahisi na nafuu kwa mfanyabiashara mmoja. 3) Pata leseni ya biashara kutoka Halmashauri ya wilaya/jiji (biashara ndogo) au Wizara ya Viwanda na Biashara (biashara kubwa); ada hutegemea aina ya biashara. 4) Kwa vyakula, vipodozi na dawa unahitaji usajili/kibali cha TMDA, na kwa ubora wa bidhaa TBS. 5) Fungua akaunti ya benki ya biashara na tenganisha fedha za biashara na za nyumbani. Fursa: SIDO (mafunzo, mitaji midogo na viwanda vidogo), mikopo ya Halmashauri kwa vikundi vya vijana, wanawake na watu wenye ulemavu (uliza Afisa Maendeleo ya Jamii wa halmashauri kuhusu utaratibu wa sasa), VICOBA na SACCOS. Andika mpango wa biashara rahisi: bidhaa, wateja, gharama, bei, faida.',
                'keywords' => ['biashara', 'kusajili biashara', 'brela', 'leseni ya biashara', 'tin', 'sido', 'mtaji', 'mpango wa biashara', 'ujasiriamali', 'tmda', 'tbs', 'mikopo ya halmashauri'],
                'source' => 'BRELA; TRA; SIDO; Wizara ya Viwanda na Biashara',
            ],
            [
                'title' => 'Starting and formalising a small business in Tanzania',
                'category' => 'business', 'language' => 'en',
                'content' => 'Steps: 1) Get a TIN from TRA (free). 2) Register a business name or company with BRELA through the ORS system (ors.brela.go.tz); a business name is simpler and cheaper for a sole trader. 3) Get a business licence from the district/city council (small businesses) or the Ministry of Industry and Trade (large); fees depend on the type. 4) Food, cosmetics and medicines need TMDA registration; product quality marks come from TBS. 5) Open a business bank account and keep business and household money separate. Support: SIDO (training, small loans, industrial sheds), council loans for youth, women and persons with disabilities (ask the council Community Development Officer about the current procedure), VICOBA and SACCOS. Write a simple business plan: product, customers, costs, price, profit.',
                'keywords' => ['business', 'register business', 'brela', 'business licence', 'tin', 'sido', 'capital', 'business plan', 'entrepreneur', 'tmda', 'tbs', 'council loans'],
                'source' => 'BRELA; TRA; SIDO; Ministry of Industry and Trade',
            ],

            // ---------------------------------------------------------- FAMILY
            [
                'title' => 'Ukatili wa kijinsia na dhidi ya watoto: wapi pa kupata msaada',
                'category' => 'family', 'language' => 'sw',
                'content' => 'Ukatili wa kijinsia (kupigwa, kubakwa, kulazimishwa ndoa, kunyimwa matunzo) na ukatili dhidi ya watoto ni makosa ya jinai. Hatua: 1) Kwa hatari ya sasa piga 112 (polisi) au nenda kituo cha polisi cha karibu na uombe Dawati la Jinsia na Watoto. 2) Kwa ubakaji nenda hospitali ndani ya saa 72 kabla ya kuoga ili kupata matibabu ya kinga (PEP, dharura ya uzazi) na fomu ya PF3 (polisi hutoa PF3 bure). 3) Piga 116 (Msaada kwa Mtoto, bure, saa 24) kwa watoto. 4) Afisa Ustawi wa Jamii wa halmashauri husaidia matunzo ya watoto na makazi salama; vituo vya huduma za mkono mmoja (One Stop Centre) vipo hospitali za mikoa. 5) Msaada wa kisheria bure: TAWLA, WLAC, LHRC, Kituo cha Sheria na Haki za Binadamu, Kampeni ya Msaada wa Kisheria ya Mama Samia. Sheria ya Mtoto 2009 inakataza adhabu zinazodhuru, ajira za watoto na ndoa za utotoni.',
                'keywords' => ['ukatili', 'kupigwa', 'ubakaji', 'dawati la jinsia', '116', 'pf3', 'ustawi wa jamii', 'mtoto', 'msaada wa kisheria', 'ndoa za utotoni', 'gbv'],
                'source' => 'Sheria ya Mtoto 2009; Jeshi la Polisi; Wizara ya Maendeleo ya Jamii, Jinsia, Wanawake na Makundi Maalum',
            ],
            [
                'title' => 'Gender-based violence and violence against children: where to get help',
                'category' => 'family', 'language' => 'en',
                'content' => 'Gender-based violence (beating, rape, forced marriage, denial of maintenance) and violence against children are crimes. Steps: 1) In immediate danger call 112 or go to the nearest police station and ask for the Gender and Children\'s Desk. 2) After rape go to a hospital within 72 hours, before bathing, for preventive treatment (PEP, emergency contraception) and the PF3 form (police issue PF3 free). 3) Call 116 (Child Helpline, free, 24 hours) for children. 4) The council Social Welfare Officer helps with child maintenance and safe shelter; One Stop Centres exist at regional hospitals. 5) Free legal aid: TAWLA, WLAC, LHRC, the Mama Samia Legal Aid Campaign. The Law of the Child Act 2009 prohibits harmful punishment, child labour and child marriage.',
                'keywords' => ['violence', 'beaten', 'rape', 'gender desk', '116', 'pf3', 'social welfare', 'child', 'legal aid', 'child marriage', 'gbv', 'domestic violence'],
                'source' => 'Law of the Child Act 2009; Tanzania Police; Ministry of Community Development, Gender, Women and Special Groups',
            ],

            // ---------------------------------------------------------- TECHNOLOGY
            [
                'title' => 'Usajili wa laini ya simu na kulinda akaunti zako',
                'category' => 'technology', 'language' => 'sw',
                'content' => 'Laini zote za simu Tanzania lazima zisajiliwe kwa alama za vidole na NIN ya NIDA (watoto chini ya 18 kupitia mzazi). Laini isiyosajiliwa huzimwa. Mtu mmoja anaruhusiwa laini chache tu kwa kila mtandao; angalia laini zilizosajiliwa kwa jina lako kwa *106# na uzime usizozijua. Laini ikipotea: piga huduma kwa wateja (Vodacom 100, Yas/Tigo 100, Airtel 100, Halotel 100) kuizuia, kisha pata laini mbadala kwa kitambulisho. Usalama: tumia PIN ya simu na ya pesa isiyo tarehe ya kuzaliwa, usibonyeze viungo vya ujumbe usiovijua, washa uthibitisho wa hatua mbili WhatsApp (Mipangilio > Akaunti > Uthibitisho wa hatua mbili). Malalamiko ya huduma za mawasiliano: kwanza kwa kampuni, kisha TCRA (tcra.go.tz).',
                'keywords' => ['laini', 'sim', 'kusajili laini', 'nida', '106', 'simu imepotea', 'whatsapp', 'nywila', 'udukuzi', 'tcra', 'bando'],
                'source' => 'TCRA - tcra.go.tz',
            ],

            // ---------------------------------------------------------- TRANSPORT
            [
                'title' => 'Faini za trafiki, leseni ya udereva na ajali barabarani',
                'category' => 'transport', 'language' => 'sw',
                'content' => 'Faini ya kawaida ya makosa ya barabarani ni TZS 30,000 kwa kila kosa na hulipwa kwa control number (ujumbe wa SMS kutoka mfumo wa polisi), kwa pesa za simu au benki, si fedha taslimu kwa askari; unaweza kuhakiki faini kwa namba ya gari kupitia mfumo wa TMS. Leseni ya udereva: jifunze chuo cha udereva, fanya mtihani wa nadharia na vitendo wa Polisi wa Usalama Barabarani, kisha leseni hutolewa na TRA kwa ada kulingana na daraja (A pikipiki, B magari madogo, C magari makubwa, D mabasi). Ukipata ajali: hakikisha usalama, piga 112/115, usiondoe magari kabla ya polisi (isipokuwa kuzuia hatari), chukua picha na majina ya mashahidi, ripoti kituo cha polisi ndani ya saa 24 na pata ripoti ya ajali kwa ajili ya bima. Bima ya gari ya lazima ni ya mtu wa tatu (third party).',
                'keywords' => ['faini', 'trafiki', 'leseni ya udereva', 'ajali', 'bima ya gari', 'tms', 'control number', 'polisi wa barabarani', 'bodaboda'],
                'source' => 'Sheria ya Usalama Barabarani Sura 168; Jeshi la Polisi; TRA',
            ],

            // ---------------------------------------------------------- GENERAL
            [
                'title' => 'Tanzania kwa ufupi: historia na taarifa za msingi',
                'category' => 'general', 'language' => 'sw',
                'content' => 'Tanganyika ilipata uhuru 9 Desemba 1961 (Rais wa kwanza Mwalimu Julius Nyerere) na Zanzibar ilipata mapinduzi 12 Januari 1964; ziliungana 26 Aprili 1964 kuunda Jamhuri ya Muungano wa Tanzania. Makao makuu ni Dodoma; jiji kubwa ni Dar es Salaam. Sensa ya 2022 ilihesabu watu takriban milioni 61.7. Lugha ya taifa ni Kiswahili. Rais wa sasa ni Samia Suluhu Hassan (tangu Machi 2021). Mlima Kilimanjaro (mita 5,895) ndio mlima mrefu zaidi Afrika; Ziwa Victoria ndilo ziwa kubwa zaidi Afrika; Hifadhi ya Serengeti na Ngorongoro ni maarufu duniani. Tanzania ni mwanachama wa Jumuiya ya Afrika Mashariki (EAC), SADC na Umoja wa Afrika. Sikukuu za taifa: Mapinduzi ya Zanzibar (12 Jan), Muungano (26 Apr), Saba Saba (7 Jul), Nane Nane (8 Ago), Nyerere (14 Okt), Uhuru (9 Des).',
                'keywords' => ['tanzania', 'uhuru', 'muungano', 'nyerere', 'rais', 'dodoma', 'sensa', 'kilimanjaro', 'sikukuu', 'historia', 'idadi ya watu'],
                'source' => 'Ofisi ya Taifa ya Takwimu (NBS); Ikulu',
            ],
            [
                'title' => 'Tanzania at a glance: history and key facts',
                'category' => 'general', 'language' => 'en',
                'content' => 'Tanganyika gained independence on 9 December 1961 (first President Mwalimu Julius Nyerere); the Zanzibar Revolution was on 12 January 1964; the two united on 26 April 1964 to form the United Republic of Tanzania. The capital is Dodoma; the largest city is Dar es Salaam. The 2022 census counted about 61.7 million people. Swahili is the national language. The current President is Samia Suluhu Hassan (since March 2021). Mount Kilimanjaro (5,895 m) is Africa\'s highest mountain; Lake Victoria is Africa\'s largest lake; Serengeti and Ngorongoro are world famous. Tanzania belongs to the East African Community, SADC and the African Union. National holidays: Zanzibar Revolution (12 Jan), Union Day (26 Apr), Saba Saba (7 Jul), Nane Nane (8 Aug), Nyerere Day (14 Oct), Independence (9 Dec).',
                'keywords' => ['tanzania', 'independence', 'union', 'nyerere', 'president', 'dodoma', 'census', 'kilimanjaro', 'holidays', 'history', 'population'],
                'source' => 'National Bureau of Statistics (NBS); State House',
            ],
        ];

        foreach ($entries as $e) {
            KnowledgeEntry::query()->updateOrCreate(
                ['title' => $e['title'], 'language' => $e['language']],
                array_merge($e, ['is_active' => true, 'summary' => $e['summary'] ?? \Illuminate\Support\Str::limit($e['content'], 160)])
            );
        }

        $this->command?->info('Knowledge entries seeded: ' . count($entries));
    }
}
