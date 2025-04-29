<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAdressFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'cep' => (int) $this->faker->postcode(), 
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(), 
            'country' => $this->faker->country(),
        ];
    }
}
