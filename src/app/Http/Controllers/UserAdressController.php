<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserAdressRequest;
use App\Http\Requests\UpdateUserAdressRequest;
use App\Models\UserAdress;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class UserAdressController extends Controller
{
    public function index(StoreUserAdressRequest $request)
    {
        return UserAdress::all();
    }
    public function store(Store $store,Request $request)
    {
        $request->validate([
            "street"=>"required|string",
            "number"=>"required|integer",
            "cep"=>"required|integer",
            "city"=>"required|string",
            "state"=>"required|string",
            "country"=>"required|string",
        ]);
    }

    public function show(UserAdress $userAdress)
    {
        return $userAdress;
    }
    public function update(UpdateUserAdressRequest $request, UserAdress $userAdress)
    {
        $request->validate([
        "street"=>"required|string",
        "number"=>"required|integer",
        "cep"=>"required|integer",
        "city"=>"required|string",
        "state"=>"required|string",
        "country"=>"required|string",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAdress $userAdress)
    {
        $userAdress->delete();
    }
}
