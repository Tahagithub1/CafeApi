<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
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

        $cartItem = CartItem::where('cart_id', $cart_id)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($cartItem) {
            // Update existing item quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Add new item to cart
            $cartItem = CartItem::create([
                'cart_id' => $cart_id,
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

    public function viewCart($cart_id) {
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

    public function viewcartorder($cart_id){
//        $cart = Cart::with(['items.product' => function ($query) { $query->where('status',1); }])->find($cart_id);
          $cart = Cart::with('items.product')->find($cart_id);
          if (!$cart) {
              return response()->json([
                  'success' => false,
                  'message' => 'Cart not found',
              ],404);
          }elseif ($cart->status == 0){
              return response()->json([
                  'success' => false,
                  'message' => 'Cart is not active',
              ],200);
          }else{
              return response()->json([
                 'success' => true,
                 'message' => 'Cart is active',
                 'cart' => $cart,
              ],200);
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
    public function completeorders(Request $request){
        $validat = $request->validate([
            'table_number' => 'required|exists:carts,table_number'
        ]);
        $Cart = Cart::where('table_number',$validat['table_number'])->where('status',0)->first();
        if($Cart){
            // $Cart->update(['status' => 1]);
            // $Cart->refresh();
            // dd($Cart);
            DB::connection()->getPdo();
            DB::update('UPDATE `carts` SET `status`= 1 WHERE table_number = ? AND status = 0', [$validat['table_number']]);
            return response()->json([
                'success'=> true,
                'message'=> 'order completed successfully'
              ],201);
        }else{
            return response()->json([
                'success'=> false,
                'message'=> 'Cart not found or already compleed'
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
