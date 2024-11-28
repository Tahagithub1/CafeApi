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
        $this->emit('cartRemoved', $cartId);
        $this->carts = Cart::with('items.product')->get();
    }

    public function removeItem($itemId)
    {
        CartItem::find($itemId)->delete();
        $this->emit('itemRemoved', $itemId);
        $this->carts = Cart::with('items.product')->get();
    }

    public function render()
    {
        return view('livewire.cart-manager', ['carts' => $this->carts]);
    }
}
