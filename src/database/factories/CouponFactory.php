<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => 'CUPOM-' . $this->faker->unique()->numberBetween(1000, 9999),
            'type' => 'percentage',
            'value' => $this->faker->numberBetween(5, 30), // 5% a 30%
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'usage_limit' => $this->faker->numberBetween(50, 200),
            'used' => 0,
        ];
    }
}