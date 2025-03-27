<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class OrderFilter extends AbstractFilter
{
    #[FilterMethod]
    public function warehouse(Builder $builder, $value)
    {

    }

    #[FilterMethod]
    public function warehouseName(Builder $builder, $value)
    {

    }

    public function test()
    {

    }
}