<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Stock extends Pivot
{
    protected $table = 'stocks';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;
    protected $guarded = [];
}
