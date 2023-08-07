<?php

namespace App\Services;

use App\Http\Requests\BannerDeleteMediaRequest;
use App\Http\Requests\BannerStoreRequest;
use App\Models\Course\Banner;
use App\Models\Course\BannerContentType;
use App\Models\Course\BannerMedia;
use App\Models\Course\Course;
use App\Models\Course\Faq;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BannerService
{
    public function addImage(BannerStoreRequest $request)
    {
        $file = $request->file('banner');

        if(!empty($file)){
            $storagePath = $this->getMediaPathByType(BannerContentType::PHOTO_CONTENT_TYPE);
            if($storagePath){
                $data = (new MainService)->addMedia($storagePath, $file);
                if($data){
                    
                    Course::findOrFail($request->course_id)->update([
                        'banner_image' => $data->first()->get('name')
                    ]);
                    return $data->first()->get('url');
                }
                
            }
        }
        return null;
    }

    public function addContent(BannerStoreRequest $request)
    {
        $newBannerData = $request->except(['banner']);

        if(!empty($newBannerData)){
            $banner = Banner::updateOrCreate(['id' =>  $newBannerData['id'] ?? 0], $newBannerData);
    
    
            $storagePath = $this->getMediaPathByType(BannerContentType::PHOTO_CONTENT_TYPE);
            if($storagePath){
                $mediaData = null;
                $file = $request->file('banner');
                $data = (new MainService)->addMedia($storagePath, $file);
                if($data){
                    $mediaData = [
                        'name' => $data->first()->get('name'),
                        'url' => $data->first()->get('url'),
                        'content_type_id' => BannerContentType::PHOTO_CONTENT_TYPE
                    ];
                    $banner->media()->updateOrCreate($mediaData, $mediaData);
                }
                
            }
            return $banner;
        }
    }

    public function deleteMedia(int $media_id)
    {
        $media = BannerMedia::find($media_id);
        $path = $this->getMediaPathByType(BannerContentType::PHOTO_CONTENT_TYPE);
        if((new MainService)->deleteMedia($path, $media->name)){
            $media->delete();
            return true;
        }
        return false;
    }

    public function deleteBanner(BannerStoreRequest $request)
    {
        $path = $this->getMediaPathByType(BannerContentType::PHOTO_CONTENT_TYPE);
        $course = Course::findOrFail($request->course_id);
        if((new MainService)->deleteMedia($path, $course->banner_image)){
            $course->update(['banner_image' => null]);
            return true;
        }
        return false;
    }

    protected function getMediaPathByType($type = null)
    {
        if(!$type) return false;

        switch($type){
            case BannerContentType::PHOTO_CONTENT_TYPE:
                return Course::PATH_IMG_SIMPLE;
            case BannerContentType::VIDEO_CONTENT_TYPE:
                return Course::PATH_VIDEO;
            default:
                return false;
        }
    }
}