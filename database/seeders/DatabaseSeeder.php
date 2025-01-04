<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tickets;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créez les utilisateurs avec des rôles variés
        $this->seedUsers();

        // Créez les tickets
        $this->seedTickets();
    }

    /**
     * Seed Users.
     */
    private function seedUsers(): void
    {
        // Générer des utilisateurs aléatoires
        User::factory()->count(10)->create();

        // Ajouter des administrateurs spécifiques
        User::factory()->count(2)->state(['role' => 'admin'])->create();

        // Ajouter des techniciens spécifiques
        User::factory()->count(5)->state(['role' => 'technicien'])->create();

        // Ajouter des clients spécifiques
        User::factory()->count(20)->state(['role' => 'client'])->create();
    }

    /**
     * Seed Tickets.
     */
    private function seedTickets(): void
    {
        Tickets::factory()->count(50)->create();
    }
}
