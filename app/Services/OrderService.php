<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Меняем статус на completed
     * @param Order $order
     * @return array response
     */
    public function complete(Order $order): array
    {
        if($order->status === OrderStatusEnum::CANCELED->value) {
            return [
                'status' => false,
                'message' => 'Невозможно сменить статус с canceled на completed'
            ];
        }

        $order->update(['status' => OrderStatusEnum::COMPLETED]);

        return ['status' => true];
    }

    public function cancel(Order $order): array
    {
        Log::channel('single')->debug('tut1');
        if($order->status === OrderStatusEnum::COMPLETED->value) {
            return [
                'status' => false,
                'message' => 'Невозможно сменить статус с completed на canceled'
            ];
        }

        DB::beginTransaction();
        try {
            // получаем идентификаторы и количество всех товаров заказа
            $orderItems = OrderItem::where('order_id', $order->id)->get();

            // прибавляем остаток на складе с которого был заказ
            foreach ($orderItems as $orderItem) {
                Stock::where('product_id', $orderItem->product_id)
                    ->where('warehouse_id', $order->warehouse_id)
                    ->increment('stock', $orderItem->count);
            }
            $order->update(['status' => OrderStatusEnum::CANCELED->value]);

            DB::commit();

            return ['status' => true];
        } catch (\Throwable $e) {
            Log::channel('single')->debug('tut5');
            DB::rollBack();
            throw $e;
        }
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