<?php

namespace App\Services\Order;
use App\Models\Course\Course;

use App\Models\Order\BusinessOrder;
use App\Models\Order;
use App\Models\Chat\ChatRoom;

use App\Services\Chat\BusinessChatService;

class OrderValidateActionService
{   
    // ТИПЫ ЧАТОВ
    const BUSINESS_CHAT_ONE_TO_ONE = 'business_has_one_order';
    // АCTIONS
    const REMOVE_FLOW = 'removeFlow';


    public function __construct(BusinessChatService $businessChat)
    {
        $this->businessChat = $businessChat;
    }


    public function canCreateOrder($user, $flow_id, $business_order_id)
    {
        $issetCount = \DB::table('orders')
            ->where('user_id', $user->id)
            ->where('flow_id', $flow_id)
            ->where('business_order_id', $business_order_id)
            ->count();
        // dd($flow_id);
            // dd($flow_id);
            // dd($issetCount);
        if($issetCount > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function shouldCreateChat($studentId, $business_order_id)
    {   
        $businessOrder = BusinessOrder::find($business_order_id);
        $authorChat = $businessOrder->append('author')->author['author'];
        $courses = $businessOrder->append('orderBody')->order_body['courses'];


        $chatDb = \DB::table('chat_rooms')
                ->where('type_room', $this::BUSINESS_CHAT_ONE_TO_ONE)
                ->where('business_order_id', $business_order_id)
                ->whereJsonContains('profiles', [$studentId])->get();
        if($chatDb->count() == 0) {
            // если чата нет, создаем

            // так сделано, чтобы не нарушать структуру групповых чатов
            // возможно будет функционал по расширению приватных чатов ( добавить студента и т.п)
            $profilesChat = collect([
                $studentId
            ]);

            $room = new ChatRoom;
            $room->author = $authorChat;
            $room->profiles = $profilesChat;
            $room->title = "Чат со студентом #" . $studentId . " По бизнес заявке #" .  $businessOrder->id;
            $room->avatar = null;
            $room->type_room = $this::BUSINESS_CHAT_ONE_TO_ONE;
            $room->business_order_id = $businessOrder->id;
            $room->save();
            $this->businessChat->updateUsersChatRoom($room, $businessOrder->user_id);
        } 
    }

    public function shouldDeleteChat($studentId, $business_order_id)
    {   
        $businessOrder = BusinessOrder::find($business_order_id);
        $courses = $businessOrder->append('orderBody')->order_body['courses'];

        foreach ($courses as $index => $course) {

            if(in_array($studentId, $course['students'])) {
                return '';
            } else {
                // если этот студент больше не фигурирует в зявке юр. лица, 
                // тогда удаляем его чат
                // иначе ничего не делаем

                $chatDb = \DB::table('chat_rooms')
                        ->where('type_room', $this::BUSINESS_CHAT_ONE_TO_ONE)
                        ->where('business_order_id', $business_order_id)
                        ->whereJsonContains('profiles', [$studentId])->get();
                if($chatDb->count() > 0) {
                    $room = ChatRoom::find($chatDb->first()->id);
                    $room->delete();
                } else { return ''; }
            }
        }

    }

    // различные апдейты для бизнес заявки

    public function updateBusinessOrder($businessOrder, $flowId, $action)
    {  
        $courses = $businessOrder->append('orderBody')->order_body['courses'];
        switch ($action) {
            case $this::REMOVE_FLOW :
                foreach ($courses as $index => $course) {
                    if($course['flow_id'] == $flowId ) {
                        $this->removeIndex = $index;
                        $this->students = $course['students']; 
                    }    
                }
                if (isset($this->removeIndex)) {
                    $courses = collect($courses)->reject(function ($course, $key) {
                        if($key == $this->removeIndex) {
                            return $course;
                        }
                    });
                }
                $businessOrder->order_body = collect([
                    'courses' => collect($courses)->values()
                ]);
                $businessOrder->save();

                return $this->students ?? [];
                break;
            default:
                
                break;
        }
    }
}