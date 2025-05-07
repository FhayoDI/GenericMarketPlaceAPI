<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function orderAllAdmin()
    {
        return response()->json([
            "message" => "Todos os   pedidos:",
            "order" => Order::all(),
        ]);
    }
    public function orderAllAdminActive(Order $order)
    {   
        return response()->json([
            "message" => "Pedidos ativos:",
            "order" => Order::where('status', '!=', "COMPLETED")->get()
        ]);
    }
    public function index()
    {
        return response()->json([
            "message" => "Todos os seus pedidos:",
            "orders" => Order::with(['items.product', 'coupon'])->where('user_id', auth()->id())->latest()->get(),
        ]);
    }
    public function indexOpen(Order $order)
    {
        if ($order->status !== ["COMPLETED"]) {
            return response()->json([
                "message" => "Seus pedidos ativos:",
                "order" => Order::where('user_id', auth()->id())->where('status', '!=', 'COMPLETED')->latest()->get(),
            ]);
        }
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->loadMissing([
            'items.product:id,name,price,description'
        ]);
        $formattedItems = $order->items->map(function ($item) {
            return [
                'product' => [
                    'name' => $item->product->name,
                    'description' => $item->product->description
                ],
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_item' => $item->quantity * $item->unit_price
            ];
        });
        $response = [
            'order_id' => $order->id,
            'status' => $order->status,
            'date' => $order->order_date,
            'total' => $order->total_amount,
            'items' => $formattedItems
        ];

        return response()->json([
            'message' => 'Detalhes do pedido #' . $order->id,
            'data' => $response
        ]);
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'address_id' => 'required|exists:user_adresses,id,user_id,' . $user->id,
            'coupon_code' => 'nullable|string|exists:coupons,code'
        ]);

        return DB::transaction(function () use ($validated, $user) {
            $cart = $user->cart()->with(['cartItems.product'])->firstOrFail();
            if ($cart->cartItems->isEmpty()) {
                return response()->json(['message' => 'Carrinho vazio'], 400);
            }

            $subtotal = $cart->cartItems->sum(
                fn($item) =>
                $item->product->price * $item->quantity
            );

            $coupon = null;
            $couponDiscount = 0;

            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', $validated['coupon_code'])->first();
                if (!$coupon || !$coupon->isValid() || $coupon->used >= $coupon->usage_limit) {
                    return response()->json(['message' => 'Cupom inválido'], 400);
                }


                $couponDiscount = $coupon->type === 'percentage'
                    ? $subtotal * ($coupon->value / 100)
                    : $coupon->value;

                $couponDiscount = min($couponDiscount, $subtotal);
            }

            $totalAmount = $subtotal - $couponDiscount; 

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $validated['address_id'],
                'order_date' => now(),
                'coupon_id' => $coupon?->id,
                'status' => 'PENDING',
                'subtotal' => $subtotal,
                'coupon_discount' => $couponDiscount,
                'total_amount' => $totalAmount,
            ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                ]);
                $product = Products::find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();
            }

            $cart->cartItems()->delete();

            if ($coupon) {
                $coupon->increment('used');
            }

            return response()->json([
                'order' => $order->load('items'),
                'message' => 'Pedido criado com sucesso!'
            ], 201);
        });
    }


    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            "status" => "required|in:PENDING,PROCESSING,SHIPPED,COMPLETED,CANCELED"
        ]);

        if ($order->status === "COMPLETED" && $validated['status'] !== "COMPLETED") {
            return response()->json([
                "message" => "Não é possível alterar pedidos completados!"
            ], 403);
        }
        if ($validated['status'] === "COMPLETED") {
            return response()->json([
                "message" => "Pedido entregue com sucesso!!,
                Este pedido já não pode ser alterado!",
                $order->update($validated),
            ]);
        }
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
}
