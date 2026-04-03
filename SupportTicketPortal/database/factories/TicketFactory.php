<?php

namespace Database\Factories;

use App\Models\Priority;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::all();
        $priorities = Priority::all();

        return [
            'reference_number' => strtoupper(Str::random(10)),
            'priority_id' => $priorities->random()->id,
            'created_by' => $users->random()->id,
            'assigned_by' => $users->random()->id,
            'assigned_to' => $users->random()->id,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'resolved', 'closed']),
            'first_response_at' => $this->faker->optional()->dateTime(),
            'resolved_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
