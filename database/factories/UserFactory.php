<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone_number' => '+2557' . fake()->unique()->numerify('########'),
            'email' => null,
            'password' => null,
            'role' => User::ROLE_USER,
            'is_banned' => false,
            'abuse_count' => 0,
            'opted_out' => false,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
