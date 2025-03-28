<?php

namespace App\Filters;

use App\Models\StockMove;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StockMoveFilter extends AbstractFilter
{
    /**
     * По идентификатору склада фильтрация
     * ?warehouse_id=...
     * @return void
     */
    #[FilterMethod]
    public function warehouseId(Builder $builder, $value)
    {
        $builder->whereHas('warehouse', function (Builder $query) use ($value) {
            $query->where('id', $value);
        });
    }

    /**
     * По идентификатору продукта фильтрация
     * ?product_id=...
     * @return void
     */
    #[FilterMethod]
    public function productId(Builder $builder, $value)
    {
        $builder->whereHas('product', function (Builder $query) use ($value) {
            $query->where('id', $value);
        });
    }

    /**
     * По конкретной дате
     * ?date=2025-03-27
     * @return void
     */
    #[FilterMethod]
    public function date(Builder $builder, $value)
    {
        $formattedDate = Carbon::parse($value)->format('Y-m-d');
        $builder->whereDate('created_at', $formattedDate);
    }
}