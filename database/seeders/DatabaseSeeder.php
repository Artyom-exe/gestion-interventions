<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tickets;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'username' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Créez des utilisateurs avec des rôles variés
        User::factory()->count(10)->create();

        // Créez des administrateurs spécifiques
        User::factory()->count(2)->admin()->create();

        // Créez des techniciens spécifiques
        User::factory()->count(5)->technicien()->create();

        // Créez des clients spécifiques
        User::factory()->count(20)->client()->create();

        Tickets::factory(50)->create();
    }
}
