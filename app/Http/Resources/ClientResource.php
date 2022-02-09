<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   
        return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'client_name' => $this->client_name,
        //     'address' => $this->address,
        //     'business_name' => $this->business_name,
        //     'contact' => $this->contact,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        // ];
    }
}
