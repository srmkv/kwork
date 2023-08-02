<?php

namespace App\Http\Resources\Course;

use App\Models\Course\Course;
use App\Models\Course\PacketDescription;
use App\Models\Course\PacketSaleRule;
use App\Traits\Path;
use Illuminate\Http\Resources\Json\JsonResource;

class PacketListResource extends JsonResource
{
    use Path;
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
            'title' => $this->title,
            'flow_id' => $this->flow_id,
            'default_price' => $this->default_price,
            'old_price' => $this->old_price,
            'instalment_price' => $this->instalment_price,
            'instalment_month_price' => $this->instalment_month_price,
            'icon' => $this->simpleImagePath($this->icon, Course::PATH_ICONS_PACKETS),
            'icon_color' => $this->icon_color,
            'date_sale_end' => $this->date_sale_end,
            'time_sale_end' => $this->time_sale_end,
            'is_limit_sales_by_date' => $this->is_limit_sales_by_date,
            'count_places' => $this->count_places,
            'is_limit_places' => $this->is_limit_places,
            'enable_sale_rules' => $this->enable_sale_rules,
            'descriptions' => PacketDescriptionResource::collection($this->descriptions),
            'sale_rules' => PacketSaleRuleResource::collection($this->saleRules),
            'tinkoff_installment' => $this->tinkoff_installment
        ];
    }
}
