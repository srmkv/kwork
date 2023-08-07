<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Services\Order\OrderCommonService;

class ListBusinessOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'created_at' => $this->created_at,
            'dates_edu' => OrderCommonService::minMaxDateOrder($this->id),
            'count_students' => OrderCommonService::countStudentsInOrder($this->id),
            'price' => $this->price,
            'status' => \DB::table('order_statuses')->where('id', $this->status_id)->first()->title
        ];
    }
}
