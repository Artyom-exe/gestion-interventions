<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Utilisateur>
 */
class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),
            'password' => 'password', // Hashed password
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'role' => $this->faker->randomElement(['admin', 'technicien', 'client']),
            'is_active' => $this->faker->boolean(80), // 80% de chances que l'utilisateur soit actif
        ];
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a technicien.
     */
    public function technicien(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'technicien',
        ]);
    }


    public function client(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'client',
        ]);
    }
}
