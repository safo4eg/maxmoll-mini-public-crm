<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMove extends Model
{
    use Filterable;
    const UPDATED_AT = null;
    public $timestamps = true;
    protected $table = 'stock_moves';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    protected function stockDifference(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => abs($this->stock_after - $this->stock_before)
        );
    }
}
