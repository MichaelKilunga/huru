<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\CommunityThread::updateOrCreate(
            ['slug' => 'general-advice'],
            [
                'title' => 'General Advice & Comments',
                'description' => 'The official place for your opinions, advice, and feedback. Verified comments here will appear on the landing page!',
                'is_system' => true,
                'is_private' => false,
            ]
        );

        \App\Models\CommunityThread::updateOrCreate(
            ['slug' => 'announcements'],
            [
                'title' => 'Official Announcements',
                'description' => 'Important updates from the HuruLearn team.',
                'is_system' => true,
                'is_private' => false,
            ]
        );
    }
}
