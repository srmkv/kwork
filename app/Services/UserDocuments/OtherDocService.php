<?php
namespace App\Services\UserDocuments;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\General\MediaItemRecource;
use App\Models\User;
use App\Models\DocOtherType;

class OtherDocService
{   

    public static function getDocs($user_id)
    {   
        return \DB::table('other_docs')
            ->where('user_id', $user_id)
            ->get();
    }

    public function delete($doc, $userId)
    {   
        $user = User::find($userId);
        if($userId == $doc->user_id) {
            // перепривязать коллекцию user_other_documents 
            // именно к самому документу, а не  юзеру, чтобы юзать без костылей TODO # 300
            if($user->getMedia('user_other_documents', 
                ['user_id' => $user->id, 'other_doc_id' => $doc->id])->count() > 0 ) 
            {
                foreach($user->getMedia('user_other_documents', ['user_id' => $user->id, 'other_doc_id' => $doc->id]) as $media  ) 
                {
                    $media->delete();
                }
            }
            $doc->delete();

            return response()->json([
                "message" => "Дополнительный документ был удален..",
                "code" => 200,
            ],200);
            
        } else {
            return response()->json([
                "message" => "Вы не можете этого сделать..",
                "code" => 403,
            ],403);
        }
    }

    public function updateType($data, $type_id)
    {
        $type = DocOtherType::find($type_id);
        $type->fill($data);
        $type->save();
        return $type;
    }

}