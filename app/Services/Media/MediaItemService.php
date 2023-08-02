<?php
namespace App\Services\Media;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\Image\Manipulations;
use App\Models\User;
//services
use App\Services\MainService;
use App\Services\Media\MediaItemService;
//resource
use App\Http\Resources\Media\ListMediaResource;
use App\Traits\Company;
class MediaItemService
{
	public function delete($media_id, $user)
	{   
	    $media_model = Media::find($media_id);
	    if(!isset($media_model)) {
	        return response()->json([
	            "message" => "Такого файла не существует..",
	            "code" => 404,                
	        ],404);
	    }
	    $collection_name = $media_model->collection_name; // 
	    $model_id = $media_model->model_id;
	    $class = new $media_model->model_type;  
	    //получим конкретную модель к которой прикреплено это фото/документ/прочее медиа
	    $currentModel = $class::find($model_id);
	    if(!isset($currentModel)){
	        return response()->json([
	            "message" => "Не найдена модель для этого медиа объекта..",
	            "code" => 404,
	        ],404);
	    }

	    // СПЕЦИФИЧНЫЕ МОДЕЛИ ГДЕ ТРЕБУЮТСЯ НЕКОТОРЫЕ ДОПОЛНИТЕЛЬНЫЕ ДЕЙСТВИЯ ПЕРЕД УДАЛЕНИЕМ ФАЙЛА
	    switch ($media_model->model_type) {
	    	case 'App\Models\Profiles\ProfileBuiseness':
	    		if($media_model->collection_name == 'profile_business_act') {
	    			$currentModel->media_act_id = null;
	    			$currentModel->save();
	    		} 
	    		if($media_model->collection_name == 'profile_business_logo') {
	    			$currentModel->media_logo_id = null;
	    			$currentModel->save();
	    		}
	    		if($media_model->collection_name == 'profile_ip_logo') {	
	    			$currentModel->media_logo_id = null;
	    			$currentModel->save();	
	    		}
	    		break;
	    	case 'App\Models\User' : 
	    		if($media_model->collection_name == 'user_snils') {
	    			$media =Media::find($user->snils->media_id);
	    			$media->delete();
	    			$user->snils->media_id = null;
	    			$user->snils->save();
	    			return response()->json([
	    			    "message" => "Файл пользователя удалён..",
	    			    "code" => 201,
	    			],202);
	    		}
	    	break;
	    	default:
	    }
		// для юзера убедимся что он удаляет именно свой файл (т.е который он и загружал)
	   	if(collect($currentModel->getMedia($collection_name, ['user_id' => $user->id]))->contains('id', $media_model->id)) {
	   	    $media_model->delete();
	   	    return response()->json([
	   	        "message" => "Файл пользователя удалён..",
	   	        "code" => 201,
	   	    ],202);
	   	} else {
	   	    return response()->json([
	   	        "message" => "У вас недостаточно прав для удаления..",
	   	        "code" => 403,             
	   	    ],403);
	   	}
	}
}