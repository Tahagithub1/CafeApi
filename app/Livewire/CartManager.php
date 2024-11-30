<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\CartItem;

class CartManager extends Component
{
    public $carts;

    public function mount()
    {
        $this->carts = Cart::with('items.product')->get();
    }

    public function removeCart($cartId)
    {
        Cart::find($cartId)->delete();
        $table_number = $cartId;
        $this->dispatch('cart-removed', ['table_number' => $table_number]);
        $this->carts = Cart::with('items.product')->get();
    }

    public function removeItem($itemId)
    {
        CartItem::find($itemId)->delete();
        $this->carts = Cart::with('items.product')->get();
    }

    public function render()
    {
        return view('livewire.cart-manager', ['carts' => $this->carts]);
    }
}
