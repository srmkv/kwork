<?php
namespace App\Services\UserDocuments;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\General\MediaItemRecource;
use App\Traits\Company;

use App\Http\Resources\Document\HigherEduResource;
use App\Http\Resources\Media\ListMediaResource;

use App\Models\HigherEdu;

class HigherEduService
{   
    public function __construct(StatusDocService $statusAction)
    {
        $this->statusAction = $statusAction;
    }

    public static function getDiploms($user_id)
    {   
        $userDiploms = \DB::table('higher_education')
            ->where('user_id', $user_id)
            ->get();
        return HigherEduResource::collection($userDiploms);

    }

    public function deleteDiplom($diplom, $userId)
    {   
        if( !isset($diplom)){
            return response()->json([
                "message" => "Диплом не найден..",
                "code" => 404,
            ],404);
        }
        if($userId == $diplom->user_id) {
            $mediaDiploms =  $diplom->getMedia('higher_diplom')->toArray();
            if(count($mediaDiploms) > 0) {
                $medias_ids = \Arr::pluck($mediaDiploms, ['id']);
                foreach ($medias_ids as $id) {
                    Media::find($id)->delete();
                }
            }
            $diplom->delete();
        } else {
            return response()->json([
                "message" => "Вы не можете этого сделать..",
                "code" => 403,
            ],403);
        }
    }

    public function new($request)
    {
        $user = Company::currentUserForAction($request);
        $diplom = new HigherEdu;
        $diplom->user_id = $user->id;
        $diplom->country_id = 1;
        $diplom->level_education_higher_id = 1;
        $diplom->city_id = 1;
        $diplom->region_id = 1;
        $diplom->save();
        return $diplom;
    }

    public function update($request)
    {
        $user = Company::currentUserForAction($request);
        $diplom_id = $request->higher_diplom_id;
        $diplom = HigherEdu::find($diplom_id);
        if( !isset($diplom)) {
            return response()->json([
                "message" => "Диплом не найден..",
                "code" => 404,
            ],404);
        }
        if($user->id == $diplom->user_id) {
            $diplom->update([
                'country_id' => $request->country_id ?? 1,
                'level_education_higher_id' => $request->level_education_higher_id ?? 1,
                'city_id' => $request->city_id ?? null,
                'city_id' => $request->city_id ?? null,
                'region_id' => $request->region_id ?? null,
                'educational_title' => $request->education_title ?? null,
                'educational_title_id' => $request->education_title_id ?? null,
                'faculty' => $request->faculty ?? null,
                'faculty_id' => $request->faculty_id ?? null,
                'speciality' => $request->speciality ?? null,
                'speciality_id' => $request->speciality_id ?? null,
                'direction_id' => $request->direction_id ?? 1,
                'study_form_id' => $request->study_form_id ?? null,
                'complited' => $request->complited ?? null,
                'year_ended' => $request->year_ended ?? null,
                'serial_number' => $request->serial_number ?? null,
            ]);
            $diplom->save();
            return $diplom;
        } else {
            return response()->json([
                "message" => "Диплом не обнаружен..",
                "code" => 404,
            ],404);
        }
    }

}