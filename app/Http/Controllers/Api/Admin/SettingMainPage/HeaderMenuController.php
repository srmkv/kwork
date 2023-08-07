<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SettingMainPage\HeaderMenu;
use App\Services\Admin\SettingMainPage\HeaderMenuService;

class HeaderMenuController extends Controller
{   
    public function __construct(HeaderMenuService $headerMenu)
    {
        $this->headerMenu = $headerMenu;
    }

    public function createSection(Request $request)
    {
        return $this->headerMenu->create($request->all());
    }

    public function editSection(Request $request)
    {
        return $this->headerMenu->update($request->all());
    }

    public function getSections(Request $request)
    {
        return HeaderMenu::all();
    }

    public function deleteSection(Request $request, $section_id)
    {
        if(HeaderMenu::find($section_id) == null) {

            return response()->json([
                'messages' => 'такого раздела не существует..',
                'code' => 404
            ], 404);
        } else {

            HeaderMenu::find($section_id)->delete();

            return response()->json([
                'messages' => 'раздел успешно удален..',
                'code' => 200
            ], 202);
        } 
    }
}
