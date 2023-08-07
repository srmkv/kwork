<?php
namespace App\Services\Chat;
use App\Models\User;
use App\Models\Chat\ChatRoom;
use App\Models\Chat\Message;
use App\Traits\Profile;

class BusinessChatService
{
    public static function updateUsersChatRoom($room, $author_id)
    {   
        if($author_id == 'delete') {
            $room->users()->sync([]);
        } else {

            $profiles = $room->append('profiles')->profiles;
            $user_ids = [];
            foreach ($profiles as $profile) {
                $user_id = Profile::getUserId($profile);
                array_push($user_ids, $user_id);
            }

            array_push($user_ids, $author_id);
            $room->users()->sync($user_ids);
        }


    }

}


