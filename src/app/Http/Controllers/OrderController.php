<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Auth\Events\Validated;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json([
            "message"=> "Todos os seus pedidos:",
            "order"=>Order::all(),
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $user = auth()->user();
        $validateddata = $request->validate([
            "adress_id"=>"required|exists:adresses,id",
            "coupon_id"=>"nullable|exist:coupon,id",
            "status"=>"required|in:PENDING,PROCESSING,SHIPPED,COMPLETED,CANCELED",
            "total_amount"=>"required|numeric|min:0",
        ]);
        $order = Order::create([
            "user_id"=>$user->id,
            "adress_id"=>$validateddata["adress_id"],
            "orderDate"=> now(),
            "coupoun_id"=>$validateddata["coupon_id"],
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
        return response()->json([
            "messagem"=> "Seu pedido de ". $order->id,
            "order"=>$order,
        ]);
    }
    public function update(UpdateOrderRequest $request, Order $order)
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
        $order->id->delete();
        return response()->json([
            "message"=> "Pedido removido com sucesso!!",
        ]);
    }
}
