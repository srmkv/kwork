<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Replacename;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserReplaceName;
use App\Http\Requests\Document\DeleteUserReplaceNameRequest;
use App\Http\Resources\Document\NameReplacementResource;
use App\Http\Resources\Document\EmploymentItemResource;
use App\Services\User\BusinessService;
use App\Traits\Profile;

class ChangeNameController extends Controller
{   
    public function __construct(BusinessService $business)
    {   
        $this->business = $business;
    }

    public function newDocument(Request $request)
    {   
        if(isset($request->type_company) && isset($request->company_id)) 
        {
            if($this->business->allEmployeesIntoCompany($request->type_company, $request->company_id)->contains('id', $request->profile_id)) {
               return $this->business->addChangeNameByCompany($request->profile_id);
            } else {
                return response()->json([
                    "message" => "Вы не можете создать этот документ для этого сотрудника..",
                ], 201);
            }
        }

        $user = User::find(Auth::id());
        $this->replaceName = new Replacename;
        $this->replaceName->user_id = $user->id;
        $this->replaceName->created_at = now();
        $this->replaceName->created_at = now();
        $this->replaceName->save();
        return response()->json([
            "message" => "Добавлен пустой документ на смену ФИО..",
            "code" => 201,
            "replaceName" => $this->replaceName
        ],201);
    }

    public function editDocument(Request $request)
    {   
        if(isset($request->type_company) && isset($request->company_id)){
            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('nameReplacements')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('nameReplacements')->find(Auth::id());
        }

        if(isset($request->replace_id )) {
            $replace = Replacename::find($request->replace_id);
            if($user->nameReplacements->contains($replace)) 
            {
                $replace->old_name = $request->old_name;
                $replace->old_middle_name = $request->old_middle_name;
                $replace->old_last_name = $request->old_last_name;

                $replace->new_name = $request->new_name;
                $replace->new_middle_name = $request->new_middle_name;
                $replace->new_last_name = $request->new_last_name;
                $replace->save();
                return response()->json([
                    "message" => "Информация о смене ФИО успешно сохранена..",
                    "code" => 201,
                    "replaceName" => $replace
                ],201);
            }
        }
        return response()->json([
            "message" => "Проверьте параметры..",
            "code" => 403,
        ],201);
    }


    public function loadImage(UserReplaceName $request) 
    {   
        if(isset($request->type_company) && isset($request->company_id)){
            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('nameReplacements')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('nameReplacements')->find(Auth::id());
        }
        if(isset($request->chanage_fio_image) && $request->chanage_fio_image != null  ) {
            $media = $user->addMediaFromRequest('chanage_fio_image')->withCustomProperties([
                'replace_id' => intval($request->replace_id),
                'user_id'    => intval($user->id)
            ])->toMediaCollection('user_name_replacement', 'media');
        $replace = Replacename::find($request->replace_id);
        $replace->media_id =  $media['id'];
        $replace->save();

            return response()->json([
                "message" => "Изображение загружено..",
                "code" => 201,
            ],201);
        }
        return response()->json([
            "message" => "Проверьте параметры..",
            "code" => 403,
        ],403);
    } 

    public function showImage(Request $request)
    {   
        if(isset($request->type_company) && isset($request->company_id)){
            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('nameReplacements')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('nameReplacements')->find(Auth::id());
        }

        $replace_images = $user->getMedia('user_name_replacement');
        $media_id = $request->media_id;
        $media = Media::find($media_id);
        if($media == null ) {
            return response()->json([
                "message" => "Уточните id..",
                "code" => 184
            ],403);
        }
        $employment_mime = file_get_contents($media->getPath());
        if($media->getPath()) {
             return response($employment_mime)->withHeaders([
                'Content-Type' => mime_content_type($media->getPath())
            ]);
        } else {
            return response()->json([
                "message" => "Файл не найден на сервере..",
                "code" => 186
            ],404);
        }
    }

    public function infoChanges(Request $request)
    {   
        if(isset($request->type_company) && isset($request->company_id)){
            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('nameReplacements')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('nameReplacements')->find(Auth::id());
        }
        $media_data = $user->getMedia('user_name_replacement',['user_id' => $user->id ]);
        $replace_documents = $user->nameReplacements;
        return  NameReplacementResource::collection($user->nameReplacements);
    }

    public function infoChangesById(Request $request, $fio_id)
    {   
        return NameReplacementResource::make(Replacename::find($fio_id));
    }


    public function deleteDocument(Request $request)
    {   
        if(isset($request->type_company) && isset($request->company_id)){
            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('nameReplacements')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('nameReplacements')->find(Auth::id());
        }
        $document = Replacename::find($request->replace_id);
        if ($user->nameReplacements->contains($document)){
            $media_ids = $user->getMedia('user_name_replacement', 
                                ['user_id' => $user->id, 'replace_id' => $document->id ])->pluck('id');
            if($media_ids->count() > 0) {
                Media::whereIn('id', $media_ids)->delete();
            }
            $document->delete();
            return response()->json([
                "message" => "Документ о смене имени успешно удален..",
                "code" => 202
            ],202);
        }
        return response()->json([
            "message" => "проверьте параметры..",
            "code" => 403
        ],403);
    }
}
