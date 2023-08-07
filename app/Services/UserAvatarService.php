<?php

namespace App\Services;

use App\Models\Course\Faq;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserAvatarService
{
    public function addImage($file)
    {
        $result = (new MainService)->addMedia(User::AVATAR_PATH, $file);
        auth()->user()->individualProfile->update([
            'avatar' => $result->first()['name']
        ]);
        
        return $result->first()['url'];
    }

    public function deleteImage()
    {
        if((new MainService)->deleteMedia(User::AVATAR_PATH, auth()->user()->individualProfile->avatar)){
            auth()->user()->individualProfile->update([
                'avatar' => null
            ]);
            return true;
        }
        return false;
    }

    
    
}