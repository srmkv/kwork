<?php
namespace App\Services\Admin\SettingMainPage;
use App\Models\SettingMainPage\Footer\FooterSection;
use App\Models\SettingMainPage\Footer\FooterSectionItem;
use App\Models\SettingMainPage\Footer\FooterTopLogo;
use App\Models\SettingMainPage\Footer\FooterBottomLogo;
use App\Models\SettingMainPage\Footer\FooterSocial;
use App\Models\SettingMainPage\Footer\FooterRefBlock;

use App\Services\MainService;

class FooterService
{
    public function createSection($data)
    {   
        $section = new FooterSection;
        $section->fill($data);
        $section->save();
        return $section;
    }

    public function updateSection($data, $id)
    {   
        $section = FooterSection::find($id);
        $section->fill($data);
        $section->save();
        return $section;
    }

    public function deleteSection($id)
    {
        if(FooterSection::find($id)){
            FooterSection::find($id)->delete();
            return response()->json([
                'message' => 'Раздел успешно удален..',
                'code' => 200
            ],200);
        } else {

            return response()->json([
                'message' => 'Раздела не существует или удален ранее..',
                'code' => 404
            ],404);
        }
    }

    public function all()
    {
        return FooterSection::with('items')->get();
    }

    public function createItem($data, $id)
    {
        $item = new FooterSectionItem;
        $item->footer_section_id = $id;
        $item->fill($data);
        $item->save();
        return $item;
    }

    public function updateItem($data, $id)
    {
        $item = FooterSectionItem::find($id);
        $item->fill($data);
        $item->save();
        return $item;
    }

    public function deleteItem($id)
    {
        if(FooterSectionItem::find($id)){
            FooterSectionItem::find($id)->delete();
            return response()->json([
                'message' => 'Пункт меню успешно удален..',
                'code' => 200,
                'section' => FooterSection::with('items')->get()
            ],200);
        } else {

            return response()->json([
                'message' => 'Пункта меню не существует или удален ранее..',
                'code' => 404
            ],404);
        }
    }

    public function newTopLogo()
    {
        $logo = new FooterTopLogo;
        $logo->save();
        return $logo;
    }

    public function updateTopLogo($data, $id)
    {
        $logo = FooterTopLogo::find($id);
        $logo->fill($data);
        $logo->save();
        return $logo;
    }

    public function loadTopLogo($img, $id)
    {
        $topLogo = FooterTopLogo::findOrFail($id);
        if(!$topLogo->url == null){
            (new MainService)->deleteMedia(FooterTopLogo::PATH_FOOTER_TOP_LOGO, basename($topLogo->url));
        }
        $result = (new MainService)->addMedia(FooterTopLogo::PATH_FOOTER_TOP_LOGO, $img);
        $topLogo->url = $result->first()['url'];
        $topLogo->save();
        return $topLogo;      
    }

    public function deleteTopLogo($id)
    {
        $topLogo = FooterTopLogo::findOrFail($id);
        if($topLogo == null ) {
            return response()->json([
                'message' => 'Не найдено лого с таким ид..',
                'code' => 404
            ], 404);
        } else {
            (new MainService)->deleteMedia(FooterTopLogo::PATH_FOOTER_TOP_LOGO, basename($topLogo->url));
            $topLogo->delete();
            return response()->json([
                'message' => 'Удален блок с лого..',
                'code' => 200
            ], 200);
        }
    }

    public function allTopLogo()
    {
        return FooterTopLogo::all();
    }

    // 
    public function newBottomLogo()
    {
        $logo = new FooterBottomLogo;
        $logo->save();
        return $logo;
    }

    public function updateBottomLogo($data, $id)
    {
        $logo = FooterBottomLogo::find($id);
        $logo->fill($data);
        $logo->save();
        return $logo;
    }

    public function loadBottomLogo($img, $id)
    {
        $bottomLogo = FooterBottomLogo::findOrFail($id);
        if(!$bottomLogo->url == null){
            (new MainService)->deleteMedia(FooterBottomLogo::PATH_FOOTER_BOTTOM_LOGO, basename($bottomLogo->url));
        }
        $result = (new MainService)->addMedia(FooterBottomLogo::PATH_FOOTER_BOTTOM_LOGO, $img);
        $bottomLogo->url = $result->first()['url'];
        $bottomLogo->save();
        return $bottomLogo;      
    }

    public function deleteBottomLogo($id)
    {
        $bottomLogo = FooterBottomLogo::findOrFail($id);
        if($bottomLogo == null ) {
            return response()->json([
                'message' => 'Не найдено лого с таким ид..',
                'code' => 404
            ], 404);
        } else {
            (new MainService)->deleteMedia(FooterBottomLogo::PATH_FOOTER_BOTTOM_LOGO, basename($bottomLogo->url));
            $bottomLogo->delete();
            return response()->json([
                'message' => 'Удален блок с лого..',
                'code' => 200
            ], 200);
        }
    }

    public function allBottomLogo()
    {
        return FooterBottomLogo::all();
    }

    public function newSocial($data)
    {
        $social = new FooterSocial;
        $social->fill($data);
        $social->save();
        return $social;
    }

    public function updateSocial($data, $id)
    {
        $social = FooterSocial::find($id);
        $social->fill($data);
        $social->save();
        return $social;
    }

    public function deleteSocial($id)
    {
        if(FooterSocial::find($id)){
            FooterSocial::find($id)->delete();
            return response()->json([
                'message' => 'Соц сеть удалена..',
                'code' => 200
            ],200);
        } else {
            return response()->json([
                'message' => 'Соц. сети с этим ид не существует..',
                'code' => 404
            ],404);
        }
    }

    public function allSocials()
    {
        return FooterSocial::all();
    }

    public function newBlockRef($data)
    {
        $block = new FooterRefBlock;
        $block->fill($data);
        $block->save();
        return $block;
    }

    public function updateBlockRef($data, $id)
    {
        $block = FooterRefBlock::find($id);
        $block->fill($data);
        $block->save();
        return $block;
    }

    public function deleteBlockRef($id)
    {   
        // dd($id);
        if(FooterRefBlock::find($id)){
            $block = FooterRefBlock::find($id);
            $block->delete();
            return response()->json([
                'message' => 'Блок удален..',
                'code' => 200
            ],200);
        } else {
            return response()->json([
                'message' => 'Блока с этим ид не существует..',
                'code' => 404
            ],404);
        }
    }

    public function allBlocksRef()
    {
        return FooterRefBlock::all();
    }
}