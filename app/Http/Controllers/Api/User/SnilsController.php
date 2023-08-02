<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profiles\ProfileIndividual;
use App\Models\User;
use App\Models\Snils;
use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SnilsRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\Company;
use App\Services\UserDocuments\StatusDocService;
use App\Services\UserDocuments\SnilsService;

class SnilsController extends Controller
{   
    public function __construct(SnilsService $snilsAction)
    {   
        $this->snilsAction = $snilsAction;
    }


    public function newSnils(SnilsRequest $request)
    {   

        $user = Company::currentUserForAction($request);
        $snils = Snils::updateOrCreate([
            'user_id'   => $user->id,
        ],[
            'number_snils' => $request->snils_number,
            // 'status_doc' => collect([ 
            //     StatusDocService::DOC_STATUS_DEFAULT
            // ])

        ]);

        return response()->json([
            "message" => "Снилс успешно создан/изменен..",
            "code" => 201,
            "snils" => $snils
        ],201);

    }

    public function loadImageSnils(Request $request)
    {   
        $user = Company::currentUserForAction($request);

        if($user->snils->media_id == null) {
            $user->addMedia($request->snils_image)->toMediaCollection('user_snils', 'media');
            $user->snils->media_id = $user->getFirstMedia('user_snils')->toArray()['id'];
        } else {
            if( Media::find($user->snils->media_id) !== null ) {
                Media::find($user->snils->media_id)->delete();
            }
            // загрузим новую 
            $user->addMedia($request->snils_image)->toMediaCollection('user_snils', 'media');
            $user->snils->media_id = $user->getFirstMedia('user_snils')->toArray()['id'];

        }

        $user->snils->save();
        return response()->json([
            "message" => "Снилс успешно создан/изменен..",
            "code" => 201,
            "snils" => $user->snils
        ],201);
        
    }


    public function showImageSnils(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        $userSnils = $this->snilsAction->getSnils($user->id);
        if($userSnils == null) {
            return response()->json([
                "message" => "Добавьте сначала снилс..",
                "code" => 404,
            ],201);
        }
        if( $userSnils->media_id == null) {

            return response()->json([
                "message" => "Изображений нет..",
                "code" => 404,
            ],200);
        } else {

            $snils_image =  $user->getFirstMedia('user_snils');
            $snils_mime = file_get_contents($snils_image->getPath());

             return response($snils_mime)->withHeaders([
                'Content-Type' => mime_content_type($snils_image->getPath())
            ]);
        }
    }

    public function getSnils(Request $request)
    {
        $user = Company::currentUserForAction($request);
        return $this->snilsAction->getSnils($user->id) ?? collect();
    }

    public function getSnilsById(Request $request, $id)
    {
        return Snils::where('id', $id)->first();
    }


    public function deleteSnils(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $userSnils = $this->snilsAction->getSnils($user->id);

        if($userSnils !== null) {
            if( Media::find($user->snils->media_id) !== null ) {
                Media::find($user->snils->media_id)->delete();
            }
            Snils::find($userSnils->id)->delete();
            return response()->json([
                "message" => "Снилс успешно удален..",
                "code" => 201,
            ],201);
        } else {
            return response()->json([
                "message" => "У вас еще нет снилса..",
                "code" => 201,
            ],201);
        }
    }

}
