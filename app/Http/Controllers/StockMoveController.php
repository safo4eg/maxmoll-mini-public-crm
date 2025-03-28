<?php

namespace App\Http\Controllers;

use App\Filters\StockMoveFilter;
use App\Http\Resources\StockMoveResource;
use App\Models\StockMove;
use Illuminate\Http\Request;

class StockMoveController extends Controller
{
    /**
     * Посмотреть историю остатков
     */
    public function index(StockMoveFilter $filter)
    {
        // пагинация + фильтры
        // per_page количество на странице + page стандартная
        $moves = StockMove::with('product', 'warehouse')
            ->filter($filter)
            ->paginate(\request()->input('per_page', 15));

        return StockMoveResource::collection($moves);
    }
}
