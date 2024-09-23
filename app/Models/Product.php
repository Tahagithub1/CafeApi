<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'category_id',
        'photo',
        'description',
        'price'
    ];

    public function Category(){
        return $this->belongsTo(Category::class);
    }

}
