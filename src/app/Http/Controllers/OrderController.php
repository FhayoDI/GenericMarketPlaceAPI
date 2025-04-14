<?php

namespace App\Http\Controllers;

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
            "order"=>Order::all(),
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validateddata = $request->validate([
            "adress_id"=>"required|exists:user_adresses,id",
            "coupon_id"=>"nullable",
            "status"=>"required|in:PENDING,PROCESSING,SHIPPED,COMPLETED,CANCELED",
            "total_amount"=>"required|numeric|min:0",
        ]);
        
        $coupon = Coupon::find($validateddata["coupon_id"]);
        if(!$coupon){
            return response()->json([
                "message"=>"o cupon nao existe, remova ou insira um valido"
            ]);
        }
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
        $order->id->delete();
        return response()->json([
            "message"=> "Pedido removido com sucesso!!",
        ]);
    }
}
