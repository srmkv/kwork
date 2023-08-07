<?php
namespace App\Services\UserDocuments;
use Illuminate\Http\Request;
use App\Traits\Company;
use App\Models\EmploymentHistory;
use App\Http\Resources\Document\EmploymentHistoryResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class WorkBookService
{   


    public static function new($user_id)
    {   
        $workbook = new EmploymentHistory;
        $workbook->media_ids = [];
        $workbook->user_id = $user_id;
        $workbook->save();
        return EmploymentHistory::find($workbook->id);
    }

    public function delete($user, $workbook_id)
    {   
        if($user->employmentHistory()->count() > 0 && EmploymentHistory::find($workbook_id)) {
            $workbook = EmploymentHistory::find($workbook_id);
            $media_ids = $workbook->append('media_ids')->media_ids;
            if(count($media_ids) > 0) {
                Media::whereIn('id',$media_ids)->delete();
            }            
            $workbook->delete();
            return response()->json([
                "message" => "Документ полностью удален..",
                "code" => 201
            ],201);
        } else {
            return response()->json([
                "message" => "Нечего удалять..",
                "code" => 202
            ],201);
        }
    }

    public function createImage($user, $workbook_id, $data)
    {   
        $workbook = EmploymentHistory::find($workbook_id);
        $m_ids = $workbook->append('media_ids')->media_ids;

        if(isset($data->workbook_image)) {
            $media = $user->addMediaFromRequest('workbook_image')->withCustomProperties([
                'user_id' => intval($user->id),
            ])->toMediaCollection('user_workbooks');
            array_push($m_ids, $media['id']);
            $workbook->media_ids = $m_ids;
            $workbook->save();
        }
        return EmploymentHistoryResource::make($workbook);
    }

    public function showImage($user, $data)
    {
        $media_id = $data->media_id; 
        if(Media::find($media_id)) {
            $media = Media::find($media_id);
            $employment_mime = file_get_contents($media->getPath());
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

    public function deleteImage($user, $workbook_id, $data)
    {   
        $workbook = EmploymentHistory::find($workbook_id);
        $media_id = $data->media_id;
        $media_ids = $workbook->append('media_ids')->media_ids;
        if(in_array($media_id, $media_ids)){
            $media = Media::find($media_id);
            $key = array_search($media_id, $media_ids);         
            if ($key !== false) {
                 unset($media_ids[$key]);
            }
            $media->delete();
            $workbook ->media_ids = $media_ids;
            $workbook ->save();
            return EmploymentHistoryResource::make($workbook);
        } else {
            return response()->json([
                "message" => "Нет такого файла в этой трудовой..",
                "code" => 403
            ],201);
        }
    }
}
