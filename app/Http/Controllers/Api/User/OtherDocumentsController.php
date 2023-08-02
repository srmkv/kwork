<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
// типы дополнительных документов
use App\Models\DocOtherType;
// сами документы
use App\Models\OtherDoc;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Resources\Document\OtherDocumentResource;
use App\Http\Resources\Document\OtherDocumentsResource;

use App\Services\UserDocuments\StatusDocService;
use App\Services\UserDocuments\OtherDocService;

use App\Traits\Company;


class OtherDocumentsController extends Controller
{   
    public function __construct(OtherDocService $otherDocAction)
    {    
        $this->otherDocAction = $otherDocAction;      
    }

    public function moderatedTypes(Request $request)
    {
        switch ($request->moderated) {
            case '1':           
                return DocOtherType::where('moderated', 1)->get();
                break;
            case '0':
                return DocOtherType::where('moderated', 0)->get();
                break;
            default:
                return DocOtherType::all();
                break;
        }
    }

    public function newDocument(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $other_doc = new OtherDoc;
        $other_doc->type_id = $request->type_id;
        $other_doc->title = DocOtherType::find($request->type_id)->title;
        $other_doc->user_id = $user->id;
        // $other_doc->status_doc =  collect([
        //     StatusDocService::DOC_STATUS_DEFAULT
        // ]);
        $other_doc->save();
        return response()->json([
            "message" => "Новый дополнительный документ добавлен..",
            "code" => 201,
            "doc_id" => $other_doc->id
        ],201);
    }

    public function editDocument(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        $other_doc = OtherDoc::find($request->other_doc_id);
        $other_doc->title = $request->title;
        $other_doc->save();
        return $other_doc;
    }


    public function getOtherDocuments(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $userOtherDocs = $this->otherDocAction->getDocs($user->id);
        return OtherDocumentsResource::collection($userOtherDocs);
    }

    public function getOtherDocById(Request $request, $other_doc_id)
    {
        return OtherDocumentsResource::make(OtherDoc::find($other_doc_id));
    }

    public function newTypeDocument(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        $type = new DocOtherType;
        $type->title = $request->title;
        $type->save();
        return $type;
    }

    public function editTypeDocument(Request $request, $type_id)
    {   
        return $this->otherDocAction->updateType($request->only('title', 'decline_desc', 'moderated'), $type_id);
    }

    public function deleteTypeDocument(Request $request, $type_id)
    {
        $user = Company::currentUserForAction($request);
        $type = DocOtherType::find($type_id);
        $type->delete();

        return response()->json([
            "message" => "Тип удален..",
            "code" => 201,
        ],201);

    }

    public function deleteOtherDocument(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $other_doc = OtherDoc::find($request->other_type_id);
        return $this->otherDocAction->delete($other_doc, $user->id);

    }

    public function loadFiles(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $other_doc = OtherDoc::find($request->other_doc_id);

        $user->addMediaFromRequest('doc_images')->withCustomProperties([
            'user_id' => intval($user->id),
            'other_doc_id' => intval($request->other_doc_id)
        ])->toMediaCollection('user_other_documents');

        $media_id = $user->getMedia('user_other_documents')->last()->id;

        return response()->json([
            "message" => "Изображение успешно сохранено..",
            "code" => 201,
            "media_id" => $media_id
        ],201);

    }

    public function showImages(Request $request)
    {
        $user = Company::currentUserForAction($request);
        $media_id = $request->media_id;
        $media = Media::find($media_id);
        $other_mime = file_get_contents($media->getPath());

        if($media->getPath()) {
             return response($other_mime)->withHeaders([
                'Content-Type' => mime_content_type($media->getPath())
            ]);
        } else {
            return response()->json([
                "message" => "Файл не найден на сервере..",
                "code" => 186
            ],404);
        }
    }
}
