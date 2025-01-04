<?php

namespace Database\Factories;

use App\Models\Tickets;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketsFactory extends Factory
{
    protected $model = Tickets::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['ouvert', 'en_cours', 'fermé']),
            'priority' => $this->faker->randomElement(['faible', 'moyenne', 'haute']),
            'assigned_to' => User::factory()->state(['role' => 'technicien']), // Associer à un utilisateur
            'client_id' => User::factory()->state(['role' => 'client']),
        ];
    }
}
