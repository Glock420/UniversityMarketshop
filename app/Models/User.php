<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    public function addresses()
    {
        return $this->hasMany(Address::class,'user_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class,'user_id');
    }
}
