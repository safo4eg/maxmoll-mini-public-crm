<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'completed_at' => $this->completed_at,
            'warehouse' => new WarehouseResource($this->whenLoaded('warehouse')),
            'customer' => $this->customer,
            'status' => $this->status
        ];
    }
}
