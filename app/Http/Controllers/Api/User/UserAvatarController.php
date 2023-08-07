<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAvatarRequest;
use App\Http\Requests\UserAvatarShowRequest;
//facades
use Illuminate\Http\Request;
use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

//models
use App\Models\Profiles\ProfileIndividual;
use App\Models\User;
use App\Services\UserAvatarService;
use App\Traits\Path;

class UserAvatarController extends Controller
{   
    use Path;

    private $userAvatarService;

    public function __construct(UserAvatarService $userAvatarService)
    {
        $this->userAvatarService = $userAvatarService;
    }

    public function show(UserAvatarShowRequest $request)
    {
        if(Storage::disk('root')->exists(config(User::AVATAR_PATH) . '/' . $request->name)){
            if($avatar = $this->simpleImagePath($request->name, User::AVATAR_PATH)){
                return response()->json($avatar, 201);
            }
            return response()->json('Не удалось получить аватар', 404);
        }
        return response()->json('Такого аватара не существует', 404);
    }

    public function store(UserAvatarRequest $request)
    {
        $result = $this->userAvatarService->addImage($request->file('file'));
        if($result){
            return response()->json($result, 201);
        }
        return response()->json('Аватар не добавлен', 500);
    }

    public function update(UserAvatarRequest $request)
    {

    }
    
    public function destroy(Request $request)
    {
        $result = $this->userAvatarService->deleteImage();
        if($result){
            return response()->json('Аватар удалён', 201);
        }
        return response()->json('Аватар не удалён', 201);
    }
}


// $path = Storage::disk('local')->path('storage/avatars_webp/'.$hashname_webp);
// $webp = Webp::make($request->file);
