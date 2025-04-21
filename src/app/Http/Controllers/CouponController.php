<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return Coupon::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons|max:20',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'expiresAt' => 'required|date|after:now',
            'usageLimit' => 'nullable|integer|min:1'
        ]);

        $coupon = Coupon::create($validated);

        return response()->json([
            'message' => 'Cupom criado com sucesso!',
            'coupon' => $coupon
        ], 201);
    }

    public function check(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'message' => 'Cupom inválido ou expirado!'
            ], 400);
        }

        return response()->json([
            'message' => 'Cupom válido!',
            'discount' => $this->calculateDiscountValue($coupon, $request->total),
            'coupon' => $coupon
        ]);
    }

    private function calculateDiscountValue(Coupon $coupon, $total)
    {
        return $coupon->type === 'percentage' 
            ? $total * ($coupon->value / 100)
            : min($coupon->value, $total);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        
        return response()->json([
            'message' => 'Cupom removido com sucesso!'
        ]);
    }
}