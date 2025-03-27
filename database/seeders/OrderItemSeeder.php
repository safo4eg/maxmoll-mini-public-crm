<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $orderIds = DB::table('orders')->pluck('id');
        $insertingData = [];
        foreach ($orderIds as $orderId) {
            $productIds = DB::table('products')
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 9))
                ->pluck('id');
            foreach ($productIds as $productId) {
                $insertingData[] = [
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'count' => fake()->numberBetween(1, 9)
                ];
            }
        }
        Log::channel('single')->info($insertingData);
        OrderItem::create($insertingData);
    }
}
