<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'packet_name' => $this->packet->packet_name,
            'studio_name' => $this->studio->studio_name,
            'packet_price' => $this->packet->price,
            'studio_price' => $this->studio->price,
            'total_price' => $this->packet->price + $this->studio->price,
            'status_order' => ['id' => $this->statusOrder->id,
                'status_name' => $this->statusOrder->status_name],
        ];
    }
}
