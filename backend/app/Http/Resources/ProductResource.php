<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'product_name' => $this->product_name,
            'description1' => $this->description1,
            'description2' => $this->description2,
            'link' => $this->link,
            'photo' => $this->photo,
            'rating' => $this->rating,
            'categories' => $this->categories->pluck('name'),
            'tags' => $this->tags->pluck('name'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
