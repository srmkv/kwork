<?php

namespace App\Services\UserDocuments;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\General\MediaItemRecource;
use App\Services\UserDocuments\StatusDocService;
use App\Models\Snils;

class SnilsService
{
    public static function getSnils($user_id)
    {   

        return \DB::table('snils')->where('user_id', $user_id)
            // ->whereJsonContains('status_doc', StatusDocService::DOC_STATUS_DEFAULT )
            ->get()->first();
    }

    public function delete($passport)
    {

    }

}