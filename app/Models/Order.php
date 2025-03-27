<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, Filterable;
    const UPDATED_AT = null;
    public $timestamps = true;
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $guarded = false;

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Product::class,
            table: 'order_items',
            foreignPivotKey: 'order_id',
            relatedPivotKey: 'product_id',
        )
            ->as('order')
            ->withPivot('count')
            ->using(OrderItem::class);
    }
}
