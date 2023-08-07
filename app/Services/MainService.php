<?php

namespace App\Services;

use App\Models\Course\BannerType;
use App\Models\Course\Course;
use App\Models\Course\CourseDocImage;
use App\Traits\Path;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MainService
{
    use Path;

    /**
     * @param $request
     * @param $pathForMedia - путь для картинки в storage
     * @param $file
     */
    public function addMedia($pathForMedia, $file, $isUnique = true)
    {
        if(!empty($file)){
            is_array($file) ?:$file =  [$file];
            $result = collect();
            foreach ($file as $imagefile) {
                $imagefileName = $imagefile->getClientOriginalName();
                $path = config($pathForMedia) . '/' . $imagefileName;
                if($isUnique && Storage::disk('root')->exists($path)){
                    $imagefileName = $imagefile->hashName();
                    $path = config($pathForMedia) . '/' . $imagefileName;
                }
                Storage::disk('root')->put($path, file_get_contents($imagefile));
                $url = $this->simpleImagePath($imagefileName, $pathForMedia);
                $result->push(collect([
                    'url' => $url,
                    'name' => $imagefileName,
                ]));
            }
            return $result;
        }
        return false;
    }

    // всегда возвращает true ..? #todo 999
    public function deleteMedia(string $pathForMedia, string $fileName)
    {
        $path = config($pathForMedia) . '/' . $fileName;
        if(Storage::disk('root')->delete($path)){
            return true;
        }
        return false;
    }

    public static function getMediaPathByType(UploadedFile $media)
    {
        if(strstr($media->getClientMimeType(), "video/")){
            return Course::PATH_IMG_ANIMATE;
        }else if(strstr($media->getClientMimeType(), "image/")){
            return Course::PATH_IMG_SIMPLE;
        }else{
            return false;
        }
    }

    

    public static function getBannerType(UploadedFile $media)
    {
        if(strstr($media->getClientMimeType(), "video/")){
            return BannerType::ANIMATE;
        }else if(strstr($media->getClientMimeType(), "image/")){
            return BannerType::SIMPLE;
        }else{
            return false;
        }
    }

    public function deleteImages(int $imageId, string $path)
    {
        $image = CourseDocImage::findOrFail($imageId);
        if($this->deleteMedia($path, $image->name)){
            $image->delete();
            return true;
        }
        return false;
    }

    public function getPictureEditor()
    {
        $files = collect(Storage::disk('root')->allFiles(config(Course::PATH_EDITOR)));
        $files = $files->map(fn($item) => url('/') . '/storage/' . $item);
        return $files;
    }
}