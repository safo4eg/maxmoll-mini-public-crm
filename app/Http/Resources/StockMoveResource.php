<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMoveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product' => $this->product,
            'warehouse' => $this->warehouse,
            'type' => $this->type,
            'stock_before' => $this->stock_before,
            'stock_after' => $this->stock_after,
            'stocke_difference' => $this->stock_difference,
            'created_at' => $this->created_at
        ];
    }
}
