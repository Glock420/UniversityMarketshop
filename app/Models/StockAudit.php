<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAudit extends Model
{
    use HasFactory;
    protected $primaryKey = 'audit_id';
    public $timestamps = false;
}
