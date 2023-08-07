<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\FlowService;
use App\Http\Resources\Course\PacketListResource;

class FilterOrderResource extends JsonResource
{

    public function toArray($request)
    {   
        return [
            'id' => $this->id,
            'title' => $this->name,
            'is_edu_doc_required' => $this->is_edu_doc_required,
            'flows' => FlowService::infoForOrders($this->flows()),
            'packets' => PacketListResource::collection($this->listPackets),
        ];

    }
}
