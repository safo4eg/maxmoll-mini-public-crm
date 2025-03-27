<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    /** @use HasFactory<\Database\Factories\WarehouseFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'warehouses';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
