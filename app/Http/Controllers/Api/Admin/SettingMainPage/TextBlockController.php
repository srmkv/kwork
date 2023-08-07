<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\SettingMainPage\TextBlockService;

class TextBlockController extends Controller
{   
    public function __construct(TextBlockService $blockAction)
    {
        $this->blockAction = $blockAction;
    }

    public function getBlocks(Request $request)
    {
        return $this->blockAction->all();
    }

    public function updateBlock(Request $request, $id)
    {
        return $this->blockAction->update($request->only(['description', 'title', 'position']), $id);
    }

    public function updateImgBlock(Request $request, $id)
    {
        return $this->blockAction->img($request->file('file'), $id);
    }

    public function createBlock(Request $request)
    {
        return $this->blockAction->create($request->all());
    }

    public function deleteBlock(Request $request, $id)
    {
        return $this->blockAction->delete($id);
    }

    public function deleteImageBlock(Request $request, $id)
    {
        return $this->blockAction->deleteImage($id);
    }
}   

