<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@huru.local');
        $password = env('ADMIN_PASSWORD');
        $generated = false;

        if (! $password) {
            $password = Str::password(14, symbols: false);
            $generated = true;
        }

        $admin = User::query()->where('email', $email)->first();

        if (! $admin) {
            User::query()->create([
                'name' => env('ADMIN_NAME', 'Huru SMS Admin'),
                'email' => $email,
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
                'preferred_language' => 'en',
            ]);
            $this->command?->info("Admin created: {$email}");
            if ($generated) {
                $this->command?->warn("Generated admin password (set ADMIN_PASSWORD in .env to choose your own): {$password}");
            }
        } else {
            if ($admin->role !== User::ROLE_ADMIN) {
                $admin->forceFill(['role' => User::ROLE_ADMIN])->save();
            }
            $this->command?->info("Admin already exists: {$email}");
        }
    }
}
