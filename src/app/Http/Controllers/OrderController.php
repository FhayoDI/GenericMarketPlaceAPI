<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Auth\Events\Validated;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
