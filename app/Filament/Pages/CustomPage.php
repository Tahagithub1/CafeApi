<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CustomPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.custom-page';

    protected static ?string $navigationLable = 'CustomPage';

    public $carts;
         public function mount(){
            $this->carts = Cart::with('items.product')->get();
        
         }
     } 

