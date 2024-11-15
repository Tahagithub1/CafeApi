<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Model\Cart;
use App\Model\CartItem;

class CartView extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.pages.cart-view';
    // public $cartItem = [];
    // public function mount($table_number){
    //     $cart = Cart::where('table_number')->first();
    //     if($cart){
    //         $this->cartItem = $cart->items()->with('product')->get();
    //     }
    // }
    // public function render() {
    //     return view('filament.pages.cart-view-blade');
    // }
}
