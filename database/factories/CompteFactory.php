<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compte>
 */
class CompteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'id' => fake()->uuid(),
            'numero_compte' => strtoupper(fake()->unique()->bothify('########')), 
            'type_compte' => fake()->randomElement(['epargne', 'cheque']),
            'statut' => fake()->randomElement(["actif",'ferme','bloque']),
            'devise'=>'XOF',
            'client_id' => Client::factory(), 
            "motif_blocage"=>fake()->sentence(),
            "solde"=>fake()->randomFloat(),
           
        ];
    }
}
