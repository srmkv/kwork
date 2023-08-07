<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\SettingMainPage\TextBottomBlockService;

class TextBottomBlockController extends Controller
{
    public function __construct(TextBottomBlockService $block)
    {
        $this->block = $block;
    }

    public function createBlock(Request $request)
    {
        return $this->block->create($request->only('title', 'description', 'title_button', 'ref_button'));
    }

    public function updateBlock(Request $request, $id)
    {
        return $this->block->update($request->only('title', 'description', 'title_button', 'ref_button'), $id);
    }

    public function getBlocks(Request $request)
    {
        return $this->block->all();
    }

    public function updateBlockPicture(Request $request, $id)
    {   
        return $this->block->updatePicture($request->file('file'), $id);
    }

    public function deleteBlock(Request $request, $id)
    {
        return $this->block->delete($id);
    }
}
