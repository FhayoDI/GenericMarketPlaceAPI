<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

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
public function closeOrder(Cart $cart,Request $request){
        
}
}
