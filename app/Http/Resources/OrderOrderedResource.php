<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderOrderedResource extends JsonResource
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
            'code_order' => $this->code_order,
            'date_order' => date($this->created_at),
            'customer_name' => $this->user->customer->name,
            'packet_name' => $this->id,
            'studio_name' => $this->id,
            'shooting_date' => $this->shooting_date,
            'status_name' => $this->statusOrder->status_name,
        ];
    }
}
