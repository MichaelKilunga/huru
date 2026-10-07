<?php

namespace Database\Seeders;

use App\Models\ReferenceContact;
use Illuminate\Database\Seeder;

/**
 * National directory. Entries with verified_at set are universally known;
 * the rest should be checked by the operator (the admin panel flags them).
 */
class ReferenceContactSeeder extends Seeder
{
    public function run(): void
    {
        $verified = now();

        $contacts = [
            // Emergency
            ['Polisi (dharura) / Police emergency', 'police', '112', null, null, null, 'Namba ya dharura ya polisi, bure kutoka mtandao wowote.', 'Police emergency number, free from any network.', true, $verified, 1],
            ['Zimamoto na uokoaji / Fire and rescue', 'police', '114', null, null, null, 'Moto, uokoaji na majanga.', 'Fire, rescue and disasters.', true, $verified, 2],
            ['Gari la wagonjwa / Ambulance', 'health', '115', null, null, null, 'Dharura za kiafya na gari la wagonjwa.', 'Medical emergencies and ambulance.', true, $verified, 3],
            ['Msaada kwa Mtoto / National Child Helpline (C-Sema)', 'family', '116', null, null, 'https://www.sematanzania.org', 'Bure, saa 24, kwa ukatili au hatari kwa watoto na ukatili wa kijinsia.', 'Free, 24 hours, for violence or danger to children and gender-based violence.', true, $verified, 4],

            // Legal
            ['Kampeni ya Msaada wa Kisheria ya Mama Samia (Wizara ya Katiba na Sheria)', 'legal', null, null, 'km@katiba.go.tz', 'https://www.mslac.or.tz', 'Madawati ya msaada wa kisheria bure ngazi ya wilaya na mikoa; orodha ya watoa huduma ipo mslac.or.tz. S.L.P. 315 Dodoma.', 'Free legal aid desks at district and regional level; provider list at mslac.or.tz. P.O. Box 315 Dodoma.', false, null, 10],
            ['Ofisi ya Mwanasheria Mkuu wa Serikali (OAG)', 'legal', '+255 26 296 3647', null, 'info@oag.go.tz', 'https://www.agctz.go.tz', 'Mji wa Serikali Mtumba, S.L.P. 11492, Dodoma.', 'Mtumba Government City, P.O. Box 11492, Dodoma.', false, null, 11],
            ['Tume ya Haki za Binadamu na Utawala Bora (CHRAGG)', 'legal', null, null, null, 'https://www.chragg.go.tz', 'Malalamiko ya ukiukwaji wa haki za binadamu na utawala mbaya na ofisi za umma.', 'Complaints about human rights violations and maladministration by public offices.', false, null, 12],
            ['Kituo cha Sheria na Haki za Binadamu (LHRC)', 'legal', null, null, null, 'https://www.humanrights.or.tz', 'Msaada wa kisheria na elimu ya haki; ofisi Dar es Salaam, Arusha, Dodoma na mikoani.', 'Legal aid and rights education; offices in Dar es Salaam, Arusha, Dodoma and regions.', false, null, 13],
            ['Chama cha Wanasheria Wanawake Tanzania (TAWLA)', 'legal', null, null, null, 'https://www.tawla.or.tz', 'Msaada wa kisheria bure hasa kwa wanawake na watoto.', 'Free legal aid, especially for women and children.', false, null, 14],
            ['Kituo cha Msaada wa Kisheria kwa Wanawake (WLAC)', 'legal', null, null, null, 'https://www.wlac.or.tz', 'Msaada wa kisheria kwa wanawake: ndoa, mirathi, ukatili.', 'Legal aid for women: marriage, inheritance, violence.', false, null, 15],
            ['Taasisi ya Kuzuia na Kupambana na Rushwa (TAKUKURU / PCCB)', 'legal', '113', null, null, 'https://www.pccb.go.tz', 'Ripoti rushwa; namba 113 ni bure.', 'Report corruption; 113 is toll free.', false, null, 16],

            // Government
            ['NIDA - Mamlaka ya Vitambulisho vya Taifa', 'government', null, null, null, 'https://www.nida.go.tz', 'Usajili wa kitambulisho cha taifa (NIN). Hakiki hali kwa *152*00#.', 'National ID registration (NIN). Check status via *152*00#.', false, null, 20],
            ['RITA - Wakala wa Usajili, Ufilisi na Udhamini', 'government', null, null, null, 'https://www.rita.go.tz', 'Vyeti vya kuzaliwa, kifo, ndoa na talaka.', 'Birth, death, marriage and divorce certificates.', false, null, 21],
            ['Idara ya Uhamiaji', 'government', null, null, null, 'https://www.immigration.go.tz', 'Pasipoti, viza na vibali vya ukaazi. Maombi: eservices.immigration.go.tz', 'Passports, visas and residence permits. Apply at eservices.immigration.go.tz', false, null, 22],
            ['BRELA - Wakala wa Usajili wa Biashara na Leseni', 'business', null, null, null, 'https://www.brela.go.tz', 'Usajili wa majina ya biashara na kampuni kupitia ORS.', 'Business name and company registration through ORS.', false, null, 23],
            ['TANESCO - Shirika la Umeme Tanzania', 'government', null, null, null, 'https://www.tanesco.co.tz', 'Kuunganishwa umeme, LUKU na hitilafu za umeme.', 'Electricity connection, LUKU tokens and faults.', false, null, 24],
            ['Sekretarieti ya Ajira katika Utumishi wa Umma', 'employment', null, null, null, 'https://www.ajira.go.tz', 'Nafasi rasmi za kazi serikalini; maombi ni bure.', 'Official public service vacancies; applications are free.', false, null, 25],

            // Finance
            ['TRA - Mamlaka ya Mapato Tanzania', 'finance', '0800 750 075', '0800 780 078', null, 'https://www.tra.go.tz', 'TIN, kodi, leseni za udereva, usajili wa magari. Namba za bure.', 'TIN, taxes, driving licences, vehicle registration. Toll-free numbers.', false, null, 30],
            ['Benki Kuu ya Tanzania (BoT)', 'finance', null, null, null, 'https://www.bot.go.tz', 'Orodha ya benki na watoa mikopo waliosajiliwa; malalamiko ya huduma za fedha.', 'List of licensed banks and lenders; financial service complaints.', false, null, 31],
            ['NSSF - Mfuko wa Taifa wa Hifadhi ya Jamii', 'employment', null, null, null, 'https://www.nssf.or.tz', 'Michango na mafao ya wafanyakazi wa sekta binafsi na wanaojiajiri.', 'Contributions and benefits for private sector and self-employed workers.', false, null, 32],

            // Health
            ['NHIF - Mfuko wa Taifa wa Bima ya Afya', 'health', null, null, null, 'https://www.nhif.or.tz', 'Bima ya afya ya umma; vifurushi vya hiari kwa wananchi.', 'Public health insurance; voluntary packages for citizens.', false, null, 40],
            ['Wizara ya Afya', 'health', null, null, null, 'https://www.moh.go.tz', 'Miongozo ya afya, chanjo na taarifa za milipuko.', 'Health guidelines, vaccination and outbreak information.', false, null, 41],
            ['TMDA - Mamlaka ya Dawa na Vifaa Tiba', 'health', null, null, null, 'https://www.tmda.go.tz', 'Usalama wa dawa, vyakula na vipodozi; ripoti dawa bandia.', 'Safety of medicines, food and cosmetics; report counterfeit medicines.', false, null, 42],

            // Education
            ['NECTA - Baraza la Mitihani la Tanzania', 'education', null, null, null, 'https://www.necta.go.tz', 'Matokeo ya mitihani ya taifa na ratiba.', 'National examination results and timetables.', false, null, 50],
            ['HESLB - Bodi ya Mikopo ya Wanafunzi wa Elimu ya Juu', 'education', null, null, null, 'https://www.heslb.go.tz', 'Mikopo ya elimu ya juu; maombi kupitia OLAMS.', 'Higher education student loans; apply through OLAMS.', false, null, 51],
            ['TCU - Tume ya Vyuo Vikuu Tanzania', 'education', null, null, null, 'https://www.tcu.go.tz', 'Vyuo vikuu vilivyosajiliwa na mwongozo wa udahili.', 'Accredited universities and admission guidebook.', false, null, 52],
            ['VETA - Mamlaka ya Elimu na Mafunzo ya Ufundi Stadi', 'education', null, null, null, 'https://www.veta.go.tz', 'Mafunzo ya ufundi stadi na vyuo vya VETA mikoani.', 'Vocational training and VETA colleges in the regions.', false, null, 53],

            // Agriculture
            ['TARI - Taasisi ya Utafiti wa Kilimo Tanzania', 'agriculture', null, null, null, 'https://www.tari.go.tz', 'Teknolojia za kilimo, mbegu bora na ushauri wa mazao.', 'Agricultural technologies, improved seed and crop advice.', false, null, 60],
            ['TMA - Mamlaka ya Hali ya Hewa Tanzania', 'agriculture', null, null, null, 'https://www.meteo.go.tz', 'Utabiri wa mvua na hali ya hewa kwa misimu ya kilimo.', 'Rainfall and weather forecasts for farming seasons.', false, null, 61],
            ['Wizara ya Kilimo', 'agriculture', null, null, null, 'https://www.kilimo.go.tz', 'Sera, pembejeo za ruzuku na masoko ya mazao.', 'Policy, subsidised inputs and crop markets.', false, null, 62],

            // Technology
            ['TCRA - Mamlaka ya Mawasiliano Tanzania', 'technology', null, null, null, 'https://www.tcra.go.tz', 'Malalamiko ya huduma za simu na intaneti, usajili wa laini.', 'Complaints about phone and internet services, SIM registration.', false, null, 70],

            // Transport
            ['LATRA - Mamlaka ya Udhibiti Usafiri Ardhini', 'transport', null, null, null, 'https://www.latra.go.tz', 'Nauli za mabasi, leseni za usafiri na malalamiko ya abiria.', 'Bus fares, transport licences and passenger complaints.', false, null, 80],
            ['Polisi wa Usalama Barabarani (Traffic Police)', 'transport', '112', null, null, null, 'Ajali na makosa ya barabarani; faini hulipwa kwa control number.', 'Accidents and traffic offences; fines are paid via control number.', false, null, 81],
        ];

        foreach ($contacts as [$name, $category, $phone, $alt, $email, $web, $sw, $en, $emergency, $verifiedAt, $order]) {
            ReferenceContact::query()->updateOrCreate(
                ['name' => $name],
                [
                    'category' => $category, 'phone' => $phone, 'alt_phone' => $alt, 'email' => $email, 'website' => $web,
                    'description_sw' => $sw, 'description_en' => $en, 'is_emergency' => $emergency,
                    'verified_at' => $verifiedAt, 'sort_order' => $order, 'is_active' => true,
                ]
            );
        }

        $this->command?->info('Reference contacts seeded: ' . count($contacts));
    }
}
