<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            AdminUserSeeder::class,
            PromptTemplateSeeder::class,
            KnowledgeSeeder::class,
            ReferenceContactSeeder::class,
            LocalResourceSeeder::class,
            CommunitySeeder::class,
        ]);
    }
}
