<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Shape mirrors the original public API: string `id`, `category` as the
     * category name, plus
     * `categoryId`, `segment`, `features` array, and ISO timestamps.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => $this->category?->name ?? '',
            'categoryId' => (string) $this->category_id,
            'segment' => $this->segment,
            'image_url' => $this->image_url,
            'features' => $this->features ?? [],
            'status' => $this->status,
            'order_index' => $this->order_index,
            'createdAt' => optional($this->created_at)->toISOString(),
            'updatedAt' => optional($this->updated_at)->toISOString(),
        ];
    }
}
