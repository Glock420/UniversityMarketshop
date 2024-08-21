<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;
    protected $primaryKey = 'exchange_id';
    public $timestamps = false;

    public function exchangeItems()
    {
        return $this->hasMany(ExchangeItem::class,'exchange_id','exchange_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class,'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class,'buyer_id','user_id');
    }
}