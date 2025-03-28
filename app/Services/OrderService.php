<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Enums\StockErrorCodeEnum;
use App\Exceptions\StockManipulationException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
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
    public function createOrder(mixed $data)
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer' => $data['customer'],
                'warehouse_id' => $data['warehouse_id'],
                'status' => $data['status'],
            ]);

            // проверка связи продукта и склада
            foreach ($data['products'] as $product) {
                $productExists = Stock::where('warehouse_id', $order->warehouse_id)
                    ->where('product_id', $product['id'])
                    ->exists();

                if(!$productExists) {
                    throw new StockManipulationException(
                        message: 'Товар не связан со складом',
                        productId: $product['id'],
                        code: StockErrorCodeEnum::PRODUCT_NOT_FOUND_ON_WAREHOUSE->value
                    );
                }
            }

            $products = $this->getProductsArray($data['products'], $order->id);

            // вычитание из остатков
            foreach ($products as $product) {
                $this->decrementStock(
                    productId: $product['product_id'],
                    warehouseId: $data['warehouse_id'],
                    count: $product['count']
                );
            }

            OrderItem::create($products);

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
                foreach ($data['products'] as $product) {
                    $productExists = Stock::where('warehouse_id', $order->warehouse_id)
                        ->where('product_id', $product['id'])
                        ->exists();

                    if(!$productExists) {
                        throw new StockManipulationException(
                            message: 'Товар не связан со складом',
                            productId: $product['id'],
                            code: StockErrorCodeEnum::PRODUCT_NOT_FOUND_ON_WAREHOUSE->value
                        );
                    }
                }

                // перед удалением делаем инкремент для текущих
                $products = $order->products;
                foreach ($products as $product) {
                    $this->incrementStock(
                        productId: $product->id,
                        warehouseId: $order->warehouse_id,
                        count: $product->count->count
                    );
                }
                OrderItem::where('order_id', $order->id)->delete();

                // делаем декремент для будущих
                foreach ($data['products'] as $product) {
                    $this->decrementStock(
                        productId: $product['id'],
                        warehouseId: $order->warehouse_id,
                        count: $product['count']
                    );
                }
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

    /**
     * Меняем статус на canceled
     * @param Order $order
     * @return true[]|void
     * @throws StockManipulationException
     * @throws \Throwable
     */
    public function cancel(Order $order)
    {
        // если заказ завершен, то не можем отменить
        // если заказ отменен успех и ретерн, чтоб stock не обновлял
        switch ($order->status) {
            case OrderStatusEnum::COMPLETED->value:
                throw new StockManipulationException('Невозможно сменить статус с completed на canceled');
            case OrderStatusEnum::CANCELED->value:
                return;
        }

        DB::beginTransaction();
        try {
            // прибавляем остаток на складе с которого был заказ
            $products = $order->products;
            foreach ($products as $product) {
                $this->incrementStock(
                    productId: $product->id,
                    warehouseId: $order->warehouse_id,
                    count: $product->count->count
                );
            }

            $order->update(['status' => OrderStatusEnum::CANCELED->value]);

            DB::commit();

            return ['status' => true];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function resume(Order $order)
    {
        // если заказ завершен, то не можем возобновить
        // если заказ активный,то успех и return, чтоб stock не обновлялся
        switch ($order->status) {
            case OrderStatusEnum::COMPLETED->value:
                throw new StockManipulationException('Невозможно сменить статус с completed на active');
            case OrderStatusEnum::ACTIVE->value:
                return;
        }

        DB::beginTransaction();
        try {
            $products = $order->products;
            foreach ($products as $product) {
                $this->decrementStock(
                    productId: $product->id,
                    warehouseId: $order->warehouse_id,
                    count: $product->count->count
                );
            }

            $order->update(['status' => OrderStatusEnum::ACTIVE->value]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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

    /**
     * Снизить остаток товаров из заказа
     * @param array $data содержит product_id, warehouse_id, count
     * @return void
     * @throws StockManipulationException
     */
    private function decrementStock(int $productId, int $warehouseId, int $count): void
    {
        $stock = Stock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if(!$stock) {
            throw new StockManipulationException(
                message: "Склад не реализует товар",
                productId: $productId,
                code: StockErrorCodeEnum::INSUFFICIENT_STOCK->value
            );
        }

        // если остаток меньше количества => ошибка, тк отрицательное получится
        if($stock->stock < $count) {
            throw new StockManipulationException(
                message: 'Недостаточное количество товаров',
                productId: $productId,
                code: StockErrorCodeEnum::INSUFFICIENT_STOCK->value
            );
        }

        // обновляем через построитель запросов
        // тк элокуент $stock->update() некорректно обновляет из-за составного ключа
        DB::table('stocks')
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->update(['stock' => $stock->stock - $count]);
    }

    private function incrementStock(int $productId, int $warehouseId, int $count): void
    {
        Stock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->increment('stock', $count);
    }
}