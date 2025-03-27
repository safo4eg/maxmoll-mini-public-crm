<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    const UPDATED_AT = null;
    public $timestamps = true;
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $guarded = false;
}
