<?php

namespace Database\Seeders;

use App\Models\LocalResource;
use Illuminate\Database\Seeder;

/**
 * Starter place-bound services. Run `php artisan huru:import-legal-aid`
 * to pull the full Mama Samia Legal Aid Campaign provider list.
 */
class LocalResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            // Legal aid (carried over from the previous platform)
            ['HAKI MAENDELEO', 'legal_aid', 'dar es salaam', 'kinondoni', 'Sinza', 'Plot 512, Sinza Madukani, Wilaya ya Kinondoni (karibu na Sinza Mori).', '+255 754 555 555', 'info@hakimaendeleo.or.tz', 'seed'],
            ['GRACE FOUNDATION', 'legal_aid', 'dar es salaam', 'kinondoni', 'Mwenge', 'S.L.P. 31238, Mwenge (Josam House), Wilaya ya Kinondoni.', '+255 222 777 777', 'info@gracefoundation.or.tz', 'seed'],
            ['DIGNITY KWANZA - COMMUNITY SOLUTIONS', 'legal_aid', 'dar es salaam', 'kinondoni', 'Mikocheni', 'S.L.P. 33035, Mikocheni B, Wilaya ya Kinondoni.', '+255 784 888 888', 'info@dignitykwanza.org', 'seed'],
            ['KAHAMA PARALEGAL AID ORGANIZATION (KAPAO)', 'legal_aid', 'shinyanga', 'kahama', null, 'Ofisi za KAPAO, Wilaya ya Kahama, Mkoa wa Shinyanga.', '+255 767 994457', 'kahamaparalegal@gmail.com', 'seed'],

            // Referral hospitals (phone numbers to be confirmed by the operator)
            ['Hospitali ya Taifa Muhimbili (MNH)', 'health', 'dar es salaam', 'ilala', 'Upanga', 'Upanga, Ilala, Dar es Salaam. Hospitali ya rufaa ya taifa.', null, null, 'seed'],
            ['Hospitali ya Rufaa ya Kanda Bugando', 'health', 'mwanza', 'nyamagana', null, 'Bugando Hill, Nyamagana, Mwanza. Hospitali ya rufaa ya Kanda ya Ziwa.', null, null, 'seed'],
            ['Hospitali ya Rufaa ya Kanda KCMC', 'health', 'kilimanjaro', 'moshi', null, 'Moshi, Kilimanjaro. Hospitali ya rufaa ya Kanda ya Kaskazini.', null, null, 'seed'],
            ['Hospitali ya Benjamin Mkapa', 'health', 'dodoma', 'dodoma', null, 'Chuo Kikuu cha Dodoma, Dodoma. Hospitali ya rufaa ya kanda.', null, null, 'seed'],
            ['Hospitali ya Rufaa ya Kanda Mbeya', 'health', 'mbeya', 'mbeya', null, 'Mbeya mjini. Hospitali ya rufaa ya Nyanda za Juu Kusini.', null, null, 'seed'],
            ['Hospitali ya Mnazi Mmoja', 'health', 'mjini magharibi', 'mjini', null, 'Mjini Unguja, Zanzibar. Hospitali ya rufaa ya Zanzibar.', null, null, 'seed'],
        ];

        foreach ($resources as [$name, $category, $region, $district, $ward, $location, $phone, $email, $source]) {
            LocalResource::query()->updateOrCreate(
                ['name' => $name],
                compact('category', 'region', 'district', 'ward', 'location', 'phone', 'email', 'source') + ['is_active' => true]
            );
        }

        $this->command?->info('Local resources seeded: ' . count($resources));
    }
}
