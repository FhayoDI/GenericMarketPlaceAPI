<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json([
            "message" => "Todos os seus pedidos:",
            "orders" => Order::with(['items.product', 'coupon'])
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->get(),
        ]);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        return response()->json([
            "message" => "Detalhes do pedido #". $order->id,
            "order" => $order->load(['items.product', 'coupon']),
        ]);
    }


    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        
        if ($order->status === "COMPLETED") {
            return response()->json([
                "message" => "Não é possível alterar pedidos completados!"
            ], 403);
        }

        $validated = $request->validate([
            "status" => "required|in:PENDING,PROCESSING,SHIPPED,COMPLETED,CANCELED"
        ]);

        $order->update($validated);

        return response()->json([
            "message" => "Status atualizado com sucesso!",
            "order" => $order,
        ]);
    }


    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if ($order->status === "COMPLETED") {
            return response()->json([
                "message" => "Não é possível excluir pedidos completados!"
            ], 403);
        }

        $order->delete();

        return response()->json([
            'message' => "Pedido removido com sucesso!",
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'coupon_code' => 'nullable|string|exists:coupons,code'
        ]);

        return DB::transaction(function () use ($user, $validated) {
            $cart = $user->cart;
            
            if ($cart->items->isEmpty()) {
                return response()->json([
                    "message" => "Seu carrinho está vazio"
                ], 422);
            }

            $subtotal = $cart->items->sum(function ($item) {
                return ($item->unit_price - $item->discount) * $item->quantity;
            });

            $coupon = !empty($validated['coupon_code'])
                ? Coupon::where('code', $validated['coupon_code'])->first()
                : null;
                
            $couponDiscount = $this->calculateDiscount($coupon, $subtotal);
            $total = $subtotal - $couponDiscount;

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $validated['address_id'],
                'order_date' => now(),
                'coupon_id' => $coupon->id ?? null,
                'status' => 'PENDING',
                'subtotal' => $subtotal,
                'total_discount' => $couponDiscount,
                'total_amount' => $total
            ]);
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            return response()->json([
                'message' => 'Pedido criado com sucesso!',
                'order' => $order->load('items.product'),
                'summary' => [
                    'subtotal' => $subtotal,
                    'discount' => $couponDiscount,
                    'total' => $total
                ]
            ], 201);
        });
    }


    private function calculateDiscount(Coupon $coupon, float $total): float
    {
        if (!$coupon || !$coupon->isValid()) {
            return 0;
        }

        $discount = $coupon->type === 'percentage' 
            ? $total * ($coupon->value / 100)
            : min($coupon->value, $total);
        
        $coupon->increment('used');
        
        return $discount;
    }
}