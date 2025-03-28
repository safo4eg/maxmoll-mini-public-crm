<?php

namespace App\Listeners;

use App\Events\StockMoveEvent;
use App\Models\StockMove;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StockMoveListener implements ShouldDispatchAfterCommit
{

    /**
     * Обработчик события
     */
    public function handle(StockMoveEvent $event): void
    {
        StockMove::create([
            'product_id' => $event->productId,
            'warehouse_id' => $event->warehouseId,
            'type' => $event->type,
            'stock_before' => $event->stockBefore,
            'stock_after' => $event->stockAfter,
        ]);
    }
}
