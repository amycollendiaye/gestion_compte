<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users, clients and comptes for tests
        \App\Models\User::factory(10)->create();
        // Call Client and Compte seeders
        $this->call([
            \Database\Seeders\ClientSeeder::class,
            \Database\Seeders\CompteSeeder::class,
            \Database\Seeders\TransactionSeeder::class,
            \Database\Seeders\AdminSeeder::class


        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
