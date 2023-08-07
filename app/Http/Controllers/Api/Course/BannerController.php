<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerDeleteMediaRequest;
use App\Http\Requests\BannerStoreRequest;
use App\Http\Resources\BannerResource;
use App\Models\Course\Banner;
use App\Services\BannerService;
use Exception;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index()
    {
        return response()->json(BannerResource::collection(Banner::all()), 201);
    }

    public function show(Banner $banner)
    {
        return response()->json(BannerResource::make($banner), 200);
    }

    public function store(BannerStoreRequest $request)
    {
        if($banner = $this->bannerService->addImage($request)){
            return response()->json($banner, 201);
        }
        return response()->json('Баннер не добавлен', 201);
    }

    public function update(BannerStoreRequest $request, Banner $banner)
    {
        return response()->json(BannerResource::make($banner->update($request->all())), 200);
    }

    public function destroy(BannerStoreRequest $request)
    {
        try{
            if($this->bannerService->deleteBanner($request)){
                return response()->json('Banner deleted', 200);
            }
            return response()->json('Banner not deleted', 200);
        }catch(Exception $e){
            return response()->json('Banner not deleted', 200);
        }
    }

    public function deleteMedia(BannerDeleteMediaRequest $request)
    {
        if($this->bannerService->deleteMedia($request->id)){
            return response()->json('Медиа файл удалён', 200);
        }
        return response()->json('Медиа файл не удалён', 200);
    }

    
}
