<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Console\StorageLinkCommand;
use Illuminate\Support\Facades\DB;



class CartController extends Controller
{
    public function createCart(Request $request)
    {
        try {
            $request->validate([
                'table_number' => 'required|numeric|min:1',
            ]);

            $cart = Cart::create([
                'table_number' => $request->input('table_number'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart created successfully',
                'id' => $cart->id,
                'data' => $cart
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {

        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query cannot be empty',
            ], 400);
        }

        $products = Product::where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No products found for the given search query.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'products' => $products,
        ], 200);
    }



    public function addItem(Request $request, $cart_id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::find($cart_id);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found',
            ], 404);
        }

        if ($cart->status !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Items cannot be added to a completed cart',
            ], 400);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {

            $cartItem->quantity += $request->quantity;
            $cartItem->save();

        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'item' => $cartItem
        ], 201);
    }

    public function viewCart($cart_id)
    {
//
//        if (!$cart_id) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Cart ID is required',
//            ], 400);
//        }

        $cart = Cart::with('items.product')->find($cart_id);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found',
            ], 404);
        }

        $totalPrice = $cart->items->reduce(function ($total, $item) {
            return $total + ($item->product->price * $item->quantity);
        }, 0);

        return response()->json([
            'success' => true,
            'message' => 'View cart',
            'cart' => $cart,
            'total_price' => $totalPrice,
        ], 200);
    }

    public function viewcartorder($cart_id)
    {
//        $cart = Cart::with(['items.product' => function ($query) { $query->where('status',1); }])->find($cart_id);
        $cart = Cart::with('items.product')->find($cart_id);
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found',
            ], 404);
        } elseif ($cart->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is not active',
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Cart is active',
                'cart' => $cart,
            ], 200);
        }
    }


    public function increaseItemQuantity(Request $request, $cartId, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::where('cart_id', $cartId)
            ->where('id', $itemId)
            ->firstOrFail();

        $cartItem->increment('quantity', $request->quantity);

        return response()->json([
            'success' => true,
            'message' => 'The number of items increased',
            'cartItem' => $cartItem
        ], 201);
    }

    public function decreaseItemQuantity(Request $request, $cartId, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::where('cart_id', $cartId)
            ->where('id', $itemId)
            ->firstOrFail();

        if ($cartItem->quantity > $request->quantity) {
            // Decrease item quantity
            $cartItem->decrement('quantity', $request->quantity);
        } else {
            // Remove item if quantity is less or equal
            $cartItem->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'The number of items decreased',
            'cartItem' => $cartItem
        ], 200);
    }

    public function completeorders(Request $request , $table_number)
    {

//        Log::info('kir' . $cart_id);
        $cart = Cart::where('table_number', $table_number)->where('status', 0)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found or not active',
            ], 404);
        }


        if ($cart->status == 1) {

            return response()->json([
                'success' => false,
                'message' => 'Cart is already completed',
            ], 400);
        }


        $cart->status = 1;
        $cart->save();

        $newCart =Cart::where('table_number', $table_number)->where('status', 0)->first();

         if (!$newCart) {
             $newCart = Cart::create([
                 'table_number' => $table_number,
                 'status' => 0,
             ]);
         }


        return response()->json([
            'success' => true,
            'message' => 'Cart completed successfully and new cart created',
            'completed_cart' => $cart,
            'new_cart' => $newCart,
        ], 200);
    }
//    public function completeorders(Request $request)
//    {
//        $validated = $request->validate([
//            'table_number' => 'required|exists:carts,table_number',
//        ]);
//        $cart = Cart::where('table_number', $validated['table_number'])->where('status', 0)->first();
//        if ($cart) {
//            // $Cart->update(['status' => 1]);
//            // $Cart->refresh();
//            // dd($Cart);
//            DB::connection()->getPdo();
//            DB::update('UPDATE carts SET status= 1 WHERE table_number = ? AND status = 0', [$validated['table_number']]);
//            return response()->json([
//                'success' => true,
//                'message' => 'order completed successfully'
//            ], 201);
//        } else {
//            return response()->json([
//                'success' => false,
//                'message' => 'Cart not found or already compleed'
//            ], 404);
//        }
//
//
//
//    }//    public function completeorders(Request $request)
//    {
//        $validated = $request->validate([
//            'table_number' => 'required|exists:carts,table_number',
//        ]);
//
//        $cart = Cart::where('table_number', $validated['table_number'])
//            ->where('status', 0)
//            ->first();
//        if ($cart){
//            $cart->status = 1;
//            $cart->save();
//            return response()->json([
//                'success' => true,
//                'message' => 'Order completed successfully!',
////            'cart' => $cart,
//            ], 201);
//        }else{
//            return response()->json(['message' => 'Cart not found or already completed'], 404);
//
//        }
//
//
//    }
    public function get_status($table_number){
        $cart = Cart::where('table_number', $table_number)->with('items.product')->first();
        if ($cart) {
            return response()->json([
                'success' => true,
                'status' => $cart->status,
                'order' => $cart,
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Order not found for this table_number.'
            ],404);
        }
    }
    public function clearcart($table_number){
        $cart = Cart::where('table_number', $table_number)->where('status',0)->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
            return response()->json([
                'success' => true,
                'message' => 'Cart has been cleared successfully!',
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Cart not found for this table_number.'
            ],404);
        }
    }


    public function removeItem($cartId, $itemId)
    {
        $cartItem = CartItem::where('cart_id', $cartId)
            ->where('id', $itemId)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ], 200);
    }
}
