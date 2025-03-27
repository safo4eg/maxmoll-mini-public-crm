<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    /**
     * Фильтрация по имени склада, параметр запроса warehouse_name
     * @param Builder $builder
     * @param $value
     * @return void
     */
    #[FilterMethod]
    public function warehouseName(Builder $builder, $value): void
    {
        $builder->whereHas('warehouse', function (Builder $builder) use ($value) {
            $builder->where('name','like', "%$value%");
        });
    }

    /**
     * Фильтрация по имени покупателя: customer
     * @param Builder $builder
     * @param $value
     * @return void
     */
    #[FilterMethod]
    public function customer(Builder $builder, $value): void
    {
        $builder->where('customer','like', "%$value%");
    }

    /**
     * Фильтрация по статусу: status
     * @param Builder $builder
     * @param $value
     * @return void
     */
    #[FilterMethod]
    public function status(Builder $builder, $value): void
    {
        $builder->where('status', $value);
    }

    /**
     * Фильтрация по идентификатору заказа: id
     * Можно передавать как массив, так и одно значение:
     * ?id[]=1&id[]=2&id[]=3
     * ?id=1
     * @param Builder $builder
     * @param $value
     * @return void
     */
    #[FilterMethod]
    public function id(Builder $builder, $value): void
    {
        if(is_array($value)) {
            $builder->whereIn('id', $value);
        } else {
            $builder->where('id', $value);
        }
    }

    /**
     * Включить в выборку обьекты со складами:
     * ?with_products=true
     * @param Builder $builder
     * @param $value
     * @return void
     */
    #[FilterMethod]
    public function withWarehouse(Builder $builder, $value): void
    {
        $builder->with('warehouse');
    }

    /**
     * Включить в выборку обьекты с продуктами:
     * ?with_products=true
     * @param Builder $builder
     * @param $value
     * @return void
     */
    #[FilterMethod]
    public function withProducts(Builder $builder, $value): void
    {
        $builder->with('products');
    }
}