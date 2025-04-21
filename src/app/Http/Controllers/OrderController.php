<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json([
            "message"=> "Todos os seus pedidos:",
            "order"=>Order::where('user_id',auth()->id())->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validateddata = $request->validate([
            "adress_id"=>"required|exists:user_adresses,id",
            "coupon_id"=>"nullable|exists:coupons,id",
            "status"=>"required|in:PENDING,PROCESSING,SHIPPED,COMPLETED,CANCELED",
            "total_amount"=>"required|numeric|min:0",
        ]);
        
        $order = Order::create([
            "user_id"=>$user->id,
            "adress_id"=>$validateddata["adress_id"],
            "orderDate"=> now(),
            "coupon_id"=>$validateddata["coupon_id"],
            "status"=>$validateddata["status"],
            "total_amount"=>$validateddata["total_amount"],
        ]);
        return response()->json(["message"=>"Pedido criado com sucesso!!","order"=>$order],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if($order->user_id !== auth()->id()){
            return response()->json([
                "message"=>"pedido inexistente",
            ],404);
        }
        
        return response()->json([
            "messagem"=> "Seu pedido de ". $order->id,
            "order"=>$order,
        ]);
    }
    public function update(Request $request, Order $order)
    {
        if ($order->status === "COMPLETED"){
            return response()->json([
                "message"=> "Não pode fazer altereações em pedidos completados!"
            ],403);
        }
        $status = $request->validate([
            "status"=>"required"
        ]);
        $order->update($status);
        return response()->json([
            "message"=> "Status do pedido atualizado com sucesso!!",
            "order"=>$order,
        ]);
    }
    public function destroy(Order $order)
    {
        if($order->user_id !== auth()->id()){
            return response()->json([
                "message"=>"você não tem permissão para excluir esse pedido",
            ]);
    }
    $order->delete();
    return response()->json([
        'message'=>"Pedido removido com sucesso!!",
    ]);
}
public function closeOrder(Request $request)
{
    $user = auth()->user();
    $cart = $user->cart;

$validated = $request->validate([
        'address_id' => 'required|exists:user_adresses,id',
        'coupon_code' => 'nullable|string|exists:coupons,code'
    ]);

$subtotal = 0;
    $itemsDetails = [];
    
    foreach ($cart->items as $item) {
        $itemSubtotal = ($item->unit_price * $item->quantity);
        $subtotal += $itemSubtotal;
        
        $itemsDetails[] = [
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'discount_applied' => $item->discount * $item->quantity
        ];
    }


    $totalDiscount = 0;
    $coupon = null;
    
    if (!empty($validated['coupon_code'])) {
        $coupon = Coupon::where('code', $validated['coupon_code'])->first();
        
        if ($coupon && $coupon->isValid()) {
            $totalDiscount = $coupon->type === 'percentage' 
                ? $subtotal * ($coupon->value / 100)
                : min($coupon->value, $subtotal);
            
            $coupon->increment('used');
        }
    }


    $order = Order::create([
        'user_id' => $user->id,
        'address_id' => $validated['address_id'],
        'orderDate' => now(),
        'coupon_id' => $coupon->id ?? null,
        'status' => 'PENDING',
        'subtotal' => $subtotal,
        'total_discount' => $totalDiscount,
        'total_amount' => $subtotal - $totalDiscount
    ]);

foreach ($cart->items as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'discount' => $item->discount
        ]);


        Products::where('id', $item->product_id)
                ->decrement('stock', $item->quantity);
    }

    $cart->items()->delete();

    return response()->json([
        'message' => 'Pedido finalizado com sucesso!',
        'order_id' => $order->id,
        'subtotal' => $subtotal,
        'desconto_itens' => array_sum(array_column($itemsDetails, 'discount_applied')),
        'desconto_cupom' => $totalDiscount,
        'total' => $order->total_amount,
        'itens' => $itemsDetails
    ], 201);
}
private function calculateDiscount(?Coupon $coupon, $total)
{
    if (!$coupon) return 0;

    return $coupon->type === 'percentage' 
        ? $total * ($coupon->value / 100)
        : min($coupon->value, $total);
}
}
