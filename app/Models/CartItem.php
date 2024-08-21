<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $primaryKey = 'cartitem_id';
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'prod_id', 'prod_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
