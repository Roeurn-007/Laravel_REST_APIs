<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'image_url' => $this->image_url,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'is_active' => (bool) $this->is_active,
            // Note: description is optional in DB. If missing, frontend will show fallback.
            'description' => $this->description,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
