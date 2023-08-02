<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessOrderResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "created_at" => $this->created_at,
            "courses" => $this->append('orderBody')->order_body['courses'],
            "pay_method_id" => $this->pay_method_id,
            "status_id" => $this->status_id,
            "price" => $this->price,
            "author" => $this->append('author')->author['author'],
        ];
    }
}
