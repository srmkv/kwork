<?php
namespace App\Services\Admin\SettingMainPage;

use App\Models\SettingMainPage\TextBottomBlock;
use App\Services\MainService;
class TextBottomBlockService
{
    public function create($data)
    {
        $block = new TextBottomBlock;
        $block->fill($data);
        $block->save();
        return $block;
    }

    public function update($data, $id)
    {   
        $block = TextBottomBlock::findOrFail($id);
        $block->fill($data);
        $block->save();
        return $block;
    }

    public function all()
    {
        return TextBottomBlock::all();
    }

    public function delete($id)
    {
        $block =  TextBottomBlock::find($id);
        if($block == null ) {
            return response()->json([
                'message' => 'Не найдено блока с таким ид..',
                'code' => 404
            ], 404);
        } else {
            (new MainService)->deleteMedia(TextBottomBlock::PATH_BOTTOM_BLOCK, basename($block->url));
            $block->delete();
            return response()->json([
                'message' => 'Текстовый блок удален с главной страницы..',
                'code' => 200
            ], 200);
        }
    }

    public function updatePicture($img, $id)
    {
        $block = TextBottomBlock::findOrFail($id);
        if(!$block->url == null){
            (new MainService)->deleteMedia(TextBottomBlock::PATH_BOTTOM_BLOCK, basename($block->url));
        }
        $result = (new MainService)->addMedia(TextBottomBlock::PATH_BOTTOM_BLOCK, $img);
        $block->url = $result->first()['url'];
        $block->save();
        return $block;        
    }
}