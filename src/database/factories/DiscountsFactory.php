<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountsFactory extends Factory
{
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['percentage', 'fixed']),
            'discount_value' => $this->faker->numberBetween(5, 50),
            'description' => $this->faker->sentence,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ];
    }
}