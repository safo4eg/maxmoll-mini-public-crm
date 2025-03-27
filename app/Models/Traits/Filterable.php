<?php

namespace App\Models\Traits;

use App\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $builder, FilterInterface $filer): Builder
    {
        $filer->apply($builder);

        return $builder;
    }

}