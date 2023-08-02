<?php
namespace App\Http\Resources\Document;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class NameReplacementResource extends JsonResource
{   
    public function toArray($request)
    {   
        $user = User::find($this->user_id);
        $media_db = $user->getMedia('user_name_replacement', ['user_id' => $user->id, 'replace_id' => $this->id])->first();
        return [
            'old_name' => $this->old_name ?? null,
            'old_middle_name' => $this->old_middle_name ?? null,
            'old_last_name' => $this->old_last_name ?? null,
            'new_name' => $this->new_name ?? null,
            'new_middle_name' => $this->new_middle_name ?? null,
            'new_last_name' => $this->new_last_name ?? null,
            'document_id' => $this->id,
            'media_id' => $media_db->id ?? null,
            'media_name' => $media_db->file_name ?? null,
        ];
    }
}

