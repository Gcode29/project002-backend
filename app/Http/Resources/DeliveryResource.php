<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
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
            'supplier' => SupplierResource::make($this->whenLoaded('supplier')),
            'items' => TransactionResource::collection($this->whenLoaded('transactions')),
            'dr_number' => $this->dr_number,
            'received_by' => $this->received_by,
            'received_at' => $this->received_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
