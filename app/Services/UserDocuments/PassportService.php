<?php

namespace App\Services\UserDocuments;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\General\MediaItemRecource;

use App\Services\UserDocuments\StatusDocService;

class PassportService
{   
    public function __construct(StatusDocService $statusAction)
    {
        $this->statusAction = $statusAction;
    }

    // получить 1 и 2 стр. изображений пасспорта
    public static function getMediaPassport($passport)
    {   
        $arrMedia = $passport->getMedia('user_passports')->toArray();
        return MediaItemRecource::collection($arrMedia);
    }

    public function delete($passport)
    {   
        $mediaPassport = $passport->getMedia('user_passports');
        if($mediaPassport->count() > 0) {
            $medias = $passport->getMedia('user_passports')->toArray();
            $medias_ids = \Arr::pluck($medias, ['id']);
            foreach ($medias_ids as $id) {
                Media::find($id)->delete();
            }
        }
        $passport->delete();
    }

}