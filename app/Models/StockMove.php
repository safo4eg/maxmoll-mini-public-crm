<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class StockMove extends Model
{
    use Filterable;
    const UPDATED_AT = null;
    public $timestamps = true;
    protected $table = 'stock_moves';
    protected $guarded = [];
    protected $primaryKey = 'id';
}
