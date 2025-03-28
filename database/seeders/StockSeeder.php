<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouseIds = DB::table('warehouses')->pluck('id');
        $insertingData = [];
        foreach ($warehouseIds as $warehouseId) {
            $productIds = DB::table('products')
                ->inRandomOrder()
                ->limit(fake()->numberBetween(1, 9))
                ->pluck('id');
            foreach ($productIds as $productId) {
                $insertingData[] = [
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'stock' => fake()->numberBetween(1, 9)
                ];
            }
        }

        Stock::create($insertingData);
    }
}
