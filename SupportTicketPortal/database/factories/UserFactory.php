<?php

namespace Database\Factories;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first = $this->faker->firstName();
        $middle = $this->faker->optional()->firstName();
        $last = $this->faker->lastName();

        return [
            'organisation_id' => Organisation::factory(),
            'first_name' => $first,
            'middle_name' => $middle,
            'last_name' => $last,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'avatar_initials' => strtoupper(substr($first, 0, 1) . substr($last, 0, 1)),
            'is_active' => true,
            'is_confirm' => true,
            'last_login_at' => now(),
            'email_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
