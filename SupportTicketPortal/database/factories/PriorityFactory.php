<?php

namespace Database\Factories;

use App\Models\Priority;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Priority>
 */
class PriorityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(['Low', 'Medium', 'High', 'Critical']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'display_order' => $this->faker->numberBetween(1, 10),
            'colour_hex' => $this->faker->hexColor(),
            'response_hours' => $this->faker->numberBetween(1, 24),
            'resolution_hours' => $this->faker->numberBetween(24, 72),
            'sla_state' => $this->faker->randomElement(['normal', 'warning', 'breach']),
            'breached' => false,
            'breached_at' => null,
            'is_active' => true,
        ];
    }
}
