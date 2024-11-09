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

                 return response()->json([
                    'success' => true,
                    'message' => 'table_number Created Successfully',
                    'cart' => $cart
                 ],201);
     }

    public function addItem(Request $request , $cart_id){
       $request->validate([
          'product_id' => 'required|exists:products,id',
          'quantity' => 'required|integer|min:1'
       ]);
       $cartItem = CartItem::where('product_id',$request->product_id)->first();
       if($cart_id){
           $cartItem->quantity += $request->quantity;
           $cartItem->save();
       }else{
           $cartItem = CartItem::create([
               'product_id' => $request->pruduct_id ,
               'quantity' => $request->quantity,
           ]);
       }
       return response()->json([
        'message'=>'Item Added To Cart Successfully' ,
         'item' =>$cartItem
        ],201);
//       $cart_item = CartItem::create([
//           'cart_id' => $cart_id,
//           'product_id' => $request->product_id,
//           'quantity' => $request->quantity,
//       ]);
//       return response()->json([$cart_item]);
    }

    public function viewCart($cart_id){

        $cart = Cart::with('items.product')->findOrFail($cart_id);
        return response()->json([
            'success' => true ,
            'message' => 'view cart',
            'cartItem' => $cartItem
        ],200);

    }

    public function increaseItemQuantity(Request $request , $cartId , $itemId){
        $cartItem = CartItem::where('cart_id' , $cartId)->where('id',$itemId)->firstOrFail();
        $cartItem->increment('quantity',$request->quantity);
        return response()->json([
            'success' => true ,
            'message' => 'he number of items increase',
            'cartItem' => $cartItem
        ],201);
    }

    public function decreaseItemQuantity(Request $request , $cartId , $itemId){
        $cartItem = CartItem::where('cart_id' , $cartId)->where('id',$itemId)->firstOrFail();

            if ($cartItem->quantity > $request->quantity){
                $cartItem->decrement('quantity',$request->quantity);
            }else{
                $cartItem->delete();
            }
            return response()->json([
                'success' => true ,
                'message' => 'he number of items decreased',
                'cartItem' => $cartItem
            ],200);
        }

    }
