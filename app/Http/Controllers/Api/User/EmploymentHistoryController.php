<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\EmploymentHistory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Document\EmploymentHistoryResource;
use App\Traits\Profile;
use App\Traits\Company;
use App\Services\UserDocuments\WorkBookService;


class EmploymentHistoryController extends Controller
{   
    public function __construct(WorkBookService $workbook)
    {
        $this->workbook = $workbook;
    }

    public function newDocument(Request $request)
    {   $user = Company::currentUserForAction($request);
        return EmploymentHistoryResource::make($this->workbook->new($user->id));
    }

    public function getDocument(Request $request)
    {
        $user = Company::currentUserForAction($request);
        return EmploymentHistoryResource::collection($user->employmentHistory);
    }

    public function editDocument(Request $request, $workbook_id)
    {   
        $user = Company::currentUserForAction($request);
        $workbook = EmploymentHistory::find($workbook_id);
        $workbook->fill($request->all());
        $workbook->save();
        return  EmploymentHistoryResource::make($workbook);
    }

    public function getDocumentById(Request $request, $workbook_id)
    {
        return EmploymentHistoryResource::make(EmploymentHistory::find($workbook_id));
    }

    public function deleteDocument(Request $request, $id)
    {
        $user = Company::currentUserForAction($request);
        return $this->workbook->delete($user, $id);
    }

    public function createImage(Request $request, $id)
    {   
        $user = Company::currentUserForAction($request);
        return $this->workbook->createImage($user, $id, $request);
    }


    public function showImage(Request $request)
    {
        $user = Company::currentUserForAction($request);
        return $this->workbook->showImage($user, $request);
    }


    public function deletePhoto(Request $request, $id)
    {
        $user = Company::currentUserForAction($request);
        return $this->workbook->deleteImage($user, $id, $request);
    }
}
