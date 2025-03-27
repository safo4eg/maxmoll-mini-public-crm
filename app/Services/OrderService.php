<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderService
 *
 * Содержит методы управления заказами
 */

class OrderService
{
    /**
     * Создание заказа
     * @param mixed $data
     * @return mixed
     */
    public function createOrder(mixed $data): void
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer' => $data['customer'],
                'warehouse_id' => $data['warehouse_id'],
                'status' => $data['status'],
            ]);

            $products = [];
            foreach ($data['products'] as $product) {
                $products[] = array_merge($product, ['order_id' => $order->id]);
            }

            OrderItem::create($products);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}