<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Address;

class AddressController extends Controller
{
    public function newAddress(Request $request)
    {   
        $user = User::find(Auth::id());

        $address = new Address;
        $address->user_id = $user->id;


        $address->address_body = $request->address_body ?? null;
        $address->entrance = $request->entrance ?? null;
        $address->apartment = $request->apartment ?? null;
        $address->choosen = $request->choosen ?? null;
        $address->save();
        return $address;

    }

    public function editAddress(Request $request, $address_id)
    {
        $user = User::find(Auth::id());
        $address = Address::find($address_id);

        $address->address_body = $request->address_body ?? null;
        $address->entrance = $request->entrance ?? null;
        $address->apartment = $request->apartment ?? null;
        $address->choosen = $request->choosen ?? null;
        $address->save();
        return $address;

    }

    public function allAddress(Request $request)
    {   
        $user = User::find(Auth::id());
        return $user->addresses;
    }

    public function getAddressChoosen(Request $request)
    {
        $user = User::find(Auth::id());
        return Address::where('user_id', $user->id)->where('choosen', 1)->first();
    }





    public function deleteAddress(Request $request, $address_id)
    {
        $user = User::find(Auth::id());


        $address = Address::find($address_id);

        if(!$address){
            return response()->json([
                'message' => 'Адреса не существует..',
            ], 201);

        }

        $address->delete();

        return response()->json([
            'message' => 'Адрес удален..',
        ], 201);

    }
}
