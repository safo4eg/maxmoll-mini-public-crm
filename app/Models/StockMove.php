<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMove extends Model
{
    const UPDATED_AT = null;
    public $timestamps = true;
    protected $table = 'stock_moves';
    protected $guarded = [];
    protected $primaryKey = 'id';
}
