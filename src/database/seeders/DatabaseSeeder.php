<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use App\Models\User;
use App\Models\UserAdress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'is_admin' => true,
            ]
        );

        Category::factory()->count(20)
            ->has(Products::factory(), 'products')
            ->create();

        User::factory()->count(10)
            ->has(UserAdress::factory()->count(count: 1), 'UserAdress') // Certificando-se de criar 1 endereço por usuário
            ->has(Cart::factory(), 'cart')
            ->create();

        Coupon::factory()->count(10)->create();

        Cart::has('cartItem')->with('cartItem')->get()->each(function ($cart) {

            $cart->user->load('UserAdress');

            $subtotal = $cart->cartItem->isEmpty() ? 0 : $cart->cartItem->sum(fn($item) => $item->unit_price * $item->quantity);

            $coupon = rand(1, 100) <= 30 ? Coupon::inRandomOrder()->first() : null;
            $couponDiscount = $coupon ? $this->calculateCouponDiscount($coupon, $subtotal) : 0;

            if ($cart->user->user_adresses->isEmpty()) {
                $cart->user->adresses->create([
                    "address" => "endereço default",
                    "city" => "default cityssss",
                    "state" => "default",
                    "zip_code" => "00000-000",
                ]);
            }

            $order = Order::create([
                'user_id' => $cart->user_id,
                'address_id' => $cart->user->user_adresses->first()->id, // Garantindo que o primeiro endereço é usado
                'order_date' => now(),
                'status' => 'PENDING',
                'coupon_id' => $coupon?->id,
                'subtotal' => $subtotal,
                'coupon_discount' => $couponDiscount,
                'total_amount' => $subtotal - $couponDiscount
            ]);

            $cart->items->each(function ($item) use ($order) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price
                ]);

                $item->product->decrement('stock', $item->quantity);
            });

            $cart->items()->delete();

            if ($coupon) {
                $coupon->increment('used');
            }
        });
    }

    private function calculateCouponDiscount(?Coupon $coupon, float $total): float
    {
        if (!$coupon || !$coupon->isValid()) return 0;

        return $coupon->type === 'percentage'
            ? $total * ($coupon->value / 100)
            : min($coupon->value, $total);
    }
}
