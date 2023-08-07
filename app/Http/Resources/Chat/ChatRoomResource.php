<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_room' => $this->type_room,
            'profiles' => $this->append('profiles')->profiles,
            'title' => $this->title,
            'avatar' => $this->avatar,
            'author' => $this->append('author')->author,
            'business_order_id' => $this->business_order_id
        ];
    }
}
