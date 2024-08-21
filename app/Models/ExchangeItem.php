<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeItem extends Model
{
    use HasFactory;
    protected $primaryKey = 'exchangeitem_id';
    public $timestamps = false;
}
