<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        $product = Products::inRandomOrder()->first() 
                ?? Products::factory()->create();
        
        $quantity = rand(1, 5);
        
        return [
            'cart_id' => \App\Models\Cart::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $product->price, 
        ];
    }
}