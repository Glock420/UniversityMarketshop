<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    protected $primaryKey = 'variation_id';
    public $timestamps = false;

    protected $fillable = [
        'prod_id',
        'color',
        'size',
        'quantity',
    ];
}