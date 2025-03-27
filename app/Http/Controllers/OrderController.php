<?php

namespace App\Http\Controllers;

use App\Filters\AbstractFilter;
use App\Filters\OrderFilter;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Получить список заказов
     */
    public function index(OrderFilter $filter)
    {
        // делаем фильтрация
        // пагинация по 15 если не указан query-параметр ?per_page={count}
        // так же можно менять страницы с дефолтного ?page={pageNumber}
        $orders = Order::filter($filter)
            ->paginate(\request()->input('per_page', 15));

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

}
