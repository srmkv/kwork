<?php
namespace App\Services\Admin\SettingMainPage;

use App\Models\SettingMainPage\TextBlock;
use App\Services\MainService;
class TextBlockService
{
    public function all()
    {   
        return TextBlock::all();
    }

    public function create($data)
    {
        $block = new TextBlock;
        $block->fill($data);
        $block->save();
        return $block;
    }

    public function delete($id)
    {
        $block = TextBlock::findOrFail($id);
        if(!$block->url == null){
            (new MainService)->deleteMedia(TextBlock::TEXT_BLOCK_PATH, basename($block->url));
        }

        $block->delete();
        return response()->json([
            'message' => 'Блок удален..',
            'code' => 200
        ],200);
    }

    public function deleteImage($id)
    {
        $block = TextBlock::findOrFail($id);
        if(!$block->url == null){
            (new MainService)->deleteMedia(TextBlock::TEXT_BLOCK_PATH, basename($block->url));
            $block->url = null;
            $block->save();
            return $block;
        } else {
            return response()->json([
                'message' => 'У этого блока нет картинки..',
                'code' => 403
            ], 403);
        }
    }

    public function update($data, $id)
    {
        $block =  TextBlock::findOrFail($id);
        $block->fill($data);
        $block->save();
        return $block;
    }

    public function img($img, $id) 
    {
        $block = TextBlock::findOrFail($id);
        if(!$block->url == null){
            (new MainService)->deleteMedia(TextBlock::TEXT_BLOCK_PATH, basename($block->url));
        }
        $result = (new MainService)->addMedia(TextBlock::TEXT_BLOCK_PATH, $img);
        $block->url = $result->first()['url'];
        $block->save();
        return $block;        
    }


}