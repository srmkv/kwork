<?php

namespace App\Http\Resources\Document;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\Company;
use App\Http\Resources\Document\ListOtherDocsResource;

class OtherDocumentsResource extends JsonResource
{

    public function toArray($request)
    {   
       
        // $user = User::find(Auth::id());
        $user = Company::currentUserForAction($request);

        return [
            'media_files' =>  ListOtherDocsResource::collection($user->getMedia('user_other_documents', ['user_id' => $user->id , 'other_doc_id' => $this->id])) ?? null,
            'other_doc_id' => $this->id,
            'title' => $this->title,
            'type_id' => $this->type_id
        ];



    }
}
