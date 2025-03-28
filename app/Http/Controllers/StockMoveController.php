<?php

namespace App\Http\Controllers;

use App\Filters\StockMoveFilter;
use Illuminate\Http\Request;

class StockMoveController extends Controller
{
    /**
     * Посмотреть историю остатков
     */
    public function index(StockMoveFilter $filter)
    {
        
    }
}
