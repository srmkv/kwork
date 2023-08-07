<?php
namespace App\Services\UserDocuments;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\General\MediaItemRecource;
use App\Services\UserDocuments\StatusDocService;

class SchoolService
{   
    public function __construct(StatusDocService $statusAction)
    {
        $this->statusAction = $statusAction;
    }

    public static function getSchools($user_id)
    {   
        return \DB::table('secondary_education')
            ->where('user_id', $user_id)
            // ->whereJsonContains('status_doc', StatusDocService::DOC_STATUS_DEFAULT )
            ->get();
    }

    public function delete($school, $userId)
    {   

        if($userId == $school->user_id) {
            $mediaSchool  = $school->getMedia('secondary_school')->toArray();
            if(count($mediaSchool) > 0) {
                $medias_ids = \Arr::pluck($mediaSchool, ['id']);
                foreach ($medias_ids as $id) {
                    Media::find($id)->delete();
                }
            }
            $school->delete();
            
        } else {
            return response()->json([
                "message" => "Вы не можете этого сделать..",
                "code" => 403,
            ],403);
        }
    }

}