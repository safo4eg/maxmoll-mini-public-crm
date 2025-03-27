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
        Order::filter($filter);

//        $orders = Order::with('warehouse')->get();
//        return OrderResource::collection($orders);
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
