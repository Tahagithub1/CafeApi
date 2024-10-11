<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartContoller extends Controller
{
    public function createCart(Request $request){

        $request->validate(['table_number' => 'required|numeric|min:1']);
                   $cart = Cart::create([
                   'table_number' => $request->input('table_number')
           ]);

                 return response()->json($cart);
     }

    public function addItem(Request $request , $cart_id){
       $request->validate([
          'product_id' => 'required|exists:products,id',
          'quantity' => 'required|integer|min:1'
       ]);
       $cart_item = CartItem::create([
           'cart_id' => $cart_id,
           'product_id' => $request->product_id,
           'quantity' => $request->quantity,
       ]);
       return response()->json([$cart_item]);
    }

    public function viewCart($cart_id){

        $cart = Cart::with('items.product')->findOrFail($cart_id);
        return response()->json($cart);

    }
}
