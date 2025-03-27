<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::with('warehouse')->get();

        $insertingData = [];
        foreach ($orders as $order) {
            $products = $order->warehouse->products()
                ->inRandomOrder()
                ->limit(5)
                ->get();
            foreach ($products as $product) {
                $insertingData[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'count' => fake()->numberBetween(1, 9)
                ];
            }
        }
        OrderItem::create($insertingData);
    }
}
