<?php

namespace App\Http\Controllers;

use App\Filters\AbstractFilter;
use App\Filters\OrderFilter;
use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\CompleteOrderRequest;
use App\Http\Requests\ResumeOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
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
    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        $attributes = $request->validated();
        $orderService->createOrder($attributes);
        return response()->json([], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order, OrderService $orderService)
    {
        $attributes = $request->validated();
        $orderService->updateOrder($attributes, $order);
        return response()->json([], 204);
    }

    public function complete(Order $order, OrderService $orderService)
    {
            $data = $orderService->complete($order);
        if($data['status']) {
            return response()->json([], 204);
        } else {
            return response()->json([
                'message' => $data['message'],
                'errors' => [
                    'complete' => $data['message']
                ]
            ], 400);
        }
    }

    public function cancel(Order $order, OrderService $orderService)
    {
        $data = $orderService->cancel($order);
        if($data['status'] === true) {
            return response()->json([], 204);
        } else {
            return response()->json([
                'message' => $data['message'],
                'errors' => [
                    'complete' => $data['message']
                ]
            ], 400);
        }
    }

    public function resume(Order $order, OrderService $orderService)
    {

    }

}
