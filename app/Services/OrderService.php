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

            OrderItem::create($this->getProductsArray($data['products'], $order->id));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateOrder(mixed $data, Order $order)
    {
        DB::beginTransaction();
        try {

            if(isset($data['customer'])) {
                Order::where('id', $order->id)
                    ->update(['customer' => $data['customer']]);
            }

            if(isset($data['products'])) {
                OrderItem::where('order_id', $order->id)->delete();
                OrderItem::create($this->getProductsArray($data['products'], $order->id));
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function complete(Order $order)
    {

    }

    public function cancel(Order $order)
    {

    }

    public function resume(Order $order)
    {

    }

    private function getProductsArray(array $inputProducts, int $orderId): array
    {
        $products = [];
        foreach ($inputProducts as $product) {
            $attributes = [
                'product_id' => $product['id'],
                'count' => $product['count']
            ];
            $products[] = array_merge($attributes, ['order_id' => $orderId]);
        }
        return $products;
    }
}