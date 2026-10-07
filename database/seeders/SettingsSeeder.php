<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use App\Support\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Settings::schema() as $key => $def) {
            SystemSetting::query()->firstOrCreate(['key' => $key], ['value' => $def['default']]);
        }

        // Environment can pre-fill the operator contact.
        if ($email = env('ADMIN_EMAIL')) {
            SystemSetting::query()->updateOrCreate(['key' => 'contact_email'], ['value' => $email]);
        }

        Settings::flush();
        $this->command?->info('Settings seeded (' . count(Settings::schema()) . ' keys).');
    }
}
