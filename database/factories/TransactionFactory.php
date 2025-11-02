<?php

namespace Database\Factories;

use App\Models\Compte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'=>fake()->uuid(),
            'type_transaction' => fake()->randomElement(['depot', 'retrait','virement','frais']),
            'statut' => fake()->randomElement(['en_attente', 'validee', 'annulee']),
            'montant' => fake()->randomFloat(2,100,100000),
            'compte_id' => Compte::factory(), 
            "description"=>fake()->sentence(),
            'date_transaction' =>now(), 
             'devise'=>fake()->randomElement(['XOF','EURO','$'])



        ];
    }
}
