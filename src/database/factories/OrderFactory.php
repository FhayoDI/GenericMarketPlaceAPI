<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAdress;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {

        $user = User::has('addresses')->inRandomOrder()->first() 
                ?? User::factory()->withAddress()->create();

        return [
            'user_id' => $user->id,
            'address_id' => $user->addresses()->first()->id, 
            'order_date' => now(),
            'status' => $this->faker->randomElement(['PENDING', 'PROCESSING', 'SHIPPED']),
            'coupon_id' => null,
            'subtotal' => $this->faker->randomFloat(2, 50, 500),
            'products_discount' => $this->faker->randomFloat(2, 0, 50),
            'coupon_discount' => $this->faker->randomFloat(2, 0, 30),
            'total_amount' => function (array $attributes) {
                return $attributes['subtotal'] 
                    - $attributes['products_discount'] 
                    - $attributes['coupon_discount'];
            }
        ];
    }
}