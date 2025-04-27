<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserAdressFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'cep' => (int) $this->faker->postcode(), // converte pra inteiro
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(), // exemplo: 'PR', 'SP'
            'country' => $this->faker->country(),
        ];
    }
}
