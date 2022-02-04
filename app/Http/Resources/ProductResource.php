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
            'name' => $this->name,
            'description' => $this->description,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'brand' => CategoryResource::make($this->whenLoaded('brand')),
            'uom' => CategoryResource::make($this->whenLoaded('uom')),
            'stocks' => $this->when($request->collect('include')->contains('stocks'), $this->stocks),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
