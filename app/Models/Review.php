<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $primaryKey = 'rev_id';
    public $timestamps = false;

    public function user()      //directly retrieve user info
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
