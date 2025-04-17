<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountsRequest;
use App\Http\Requests\UpdateDiscountsRequest;
use App\Models\Discounts;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->validate([
            "productId",
            "discount_percentage",
            "description",
            "start_date",
            "end_date"
        ]);
        Discounts::create($data);
    }
    public function update(Request $request, Discounts $discounts)
    {
        $data = $request->validate([
            "discount_percentage",
            "description",
            "start_date",
            "end_date"
        ]);
        $discounts->update($data);
        if ($discounts->end_date == date('d-m')){
            $discounts->delete();
        }
        return 201;
    }

    public function destroy(Discounts $discounts)
    {
        $discounts->delete();
    }
    public function timer(Discounts $discounts){
        if($discounts->end_date == date('d-m')){
            $discounts->delete();
        }
    }
}
