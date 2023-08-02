<?php
namespace App\Services\Admin\SettingMainPage;

use App\Models\SettingMainPage\TextBlock;
use App\Services\MainService;
class TextBlockService
{
    public function all()
    {   
        if(TextBlock::all()->count() == 0) {
            TextBlock::insert([
                [
                    'title' => '1'
                ],
                [
                    'title' => '2'
                ],
                [  
                    'title' => '3'
                ],
                [  
                    'title' => '4'
                ],
                [  
                    'title' => '5'
                ]
            ]);
        }

        return TextBlock::all();
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