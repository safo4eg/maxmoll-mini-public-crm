<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model
{
    /** @use HasFactory<\Database\Factories\WarehouseFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'warehouses';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Product::class,
            table: 'stocks',
            foreignPivotKey: 'warehouse_id',
            relatedPivotKey: 'product_id'
        )
            ->as('stock')
            ->withPivot('stock')
            ->using(Stock::class);
    }
}
