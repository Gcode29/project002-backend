<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'unique_name' => $this->unique_name,
            'name' => $this->name,
            'description' => $this->description,
            'size' => $this->size,
            'color' => $this->color,
            'selling_price' => $this->selling_price,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'brand' => BrandResource::make($this->whenLoaded('brand')),
            'uom' => UOMResource::make($this->whenLoaded('uom')),
            'stocks' => $this->when($request->collect('include')->contains('stocks'), $this->stocks),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
