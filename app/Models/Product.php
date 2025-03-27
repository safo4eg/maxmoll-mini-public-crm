<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $guarded = [];

    /**
     * Связь многие ко многи через таблицу stocks
     * @return BelongsToMany
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Warehouse::class,
            table: 'stocks',
            foreignPivotKey: 'product_id',
            relatedPivotKey: 'warehouse_id'
        )
            ->as('stock')
            ->withPivot('stock')
            ->using(Stock::class);
    }
}
