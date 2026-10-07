<?php

namespace Database\Seeders;

use App\Models\CommunityThread;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        CommunityThread::query()->updateOrCreate(
            ['slug' => 'general-advice'],
            [
                'title' => 'Maoni na Ushauri wa Jumla',
                'description' => 'Mahali rasmi pa maoni, ushauri na uzoefu wako. Maoni yaliyothibitishwa huonekana kwenye ukurasa wa mwanzo.',
                'is_system' => true,
                'is_private' => false,
            ]
        );

        CommunityThread::query()->updateOrCreate(
            ['slug' => 'announcements'],
            [
                'title' => 'Matangazo Rasmi',
                'description' => 'Taarifa muhimu kutoka kwa timu ya Huru SMS.',
                'is_system' => true,
                'is_private' => false,
            ]
        );

        foreach ([
            ['sheria-na-haki', 'Sheria na Haki', 'Jadili maswali ya sheria, haki na taratibu za mahakama na ofisi za serikali.'],
            ['kilimo-na-mifugo', 'Kilimo na Mifugo', 'Wakulima na wafugaji wakishirikiana uzoefu, bei na mbinu bora.'],
            ['afya-ya-jamii', 'Afya ya Jamii', 'Elimu ya afya, lishe na huduma za afya zilizo karibu.'],
            ['biashara-na-ajira', 'Biashara na Ajira', 'Fursa, ujasiriamali, nafasi za kazi na ushauri wa kifedha.'],
            ['wanafunzi', 'Wanafunzi na Walimu', 'Masomo, mitihani, mikopo ya HESLB na vyuo.'],
        ] as [$slug, $title, $desc]) {
            CommunityThread::query()->updateOrCreate(
                ['slug' => $slug],
                ['title' => $title, 'description' => $desc, 'is_system' => true, 'is_private' => false]
            );
        }
    }
}
