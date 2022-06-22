<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'invoice' => $this->invoice,
            'or_number' => $this->or_number,
            'payment_method' => $this->payment_method,
            'deleted_at' => $this->deleted_at,
            'items' => TransactionResource::collection($this->whenLoaded('transactions')),
            'sold_by' => UserResource::make($this->whenLoaded('receiver')),
            'sold_at' => $this->sold_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total' => abs($this->transactions->sum('total')),
        ];
    }
}
