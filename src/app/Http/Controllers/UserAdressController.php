<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserAdressRequest;
use App\Models\Historic;
use App\Models\User;
use App\Models\UserAdress;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;

class UserAdressController extends Controller
{
    public function index()
    {
        return UserAdress::all();
    }
    public function adress(Store $store,Request $request)
    {
        $request->validate([
            "street"=>"required|string",
            "number"=>"required|integer",
            "cep"=>"required|integer",
            "city"=>"required|string",
            "state"=>"required|string",
            "country"=>"required|string",
        ]);
        $adress = UserAdress::create($request->all());
        return response($adress,201);
    }

    public function show(UserAdress $userAdress,User $user)
    {
        return response()->json([
            "usuario:"=>$user,
            "endereço:"=>$userAdress,
        ]);
    }
    public function update(UpdateUserAdressRequest $request, UserAdress $userAdress,User $user)
    {
        $newAdress = $request->validate([
        "street"=>"required|string",
        "number"=>"required|integer",
        "cep"=>"required|integer",
        "city"=>"required|string",
        "state"=>"required|string",
        "country"=>"required|string",
        ]);
        $userAdress->update($newAdress);
        return response()->json([
            "User"=>$user,
            "userAdress:"=>$userAdress,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAdress $userAdress)
    {
        $userAdress->delete();
    }
    public function addHistoric(UserAdress $userAdress,Request $request)
    {
        $request->validate([
            'street',
            'number',
            'cep',
            'city',
            'state',
            'country',
        ]);  
        $historic = Historic::create($request->all());
        return response()->json([
            "historic"=>$historic,
        ]);
        
    }

}
