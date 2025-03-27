<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer' => $this->faker->name(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-5 month'),
            'completed_at' => $this->faker->optional('0.5')
                ->dateTimeBetween('-4 month', 'now'),
            'warehouse_id' => DB::table('warehouses')->inRandomOrder()->first()->id,
            'status' => collect(OrderStatusEnum::getValues())->random()
        ];
    }
}
