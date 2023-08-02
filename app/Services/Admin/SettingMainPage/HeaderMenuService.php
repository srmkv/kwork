<?php
namespace App\Services\Admin\SettingMainPage;

use App\Services\MainService;

use App\Models\SettingMainPage\HeaderMenu;

class HeaderMenuService
{
    public function create($data)
    {
        $menuItem = new HeaderMenu;
        $menuItem->url = $data['url'] ?? '';
        $menuItem->title_section  = $data['title'] ?? null;
        $menuItem->save();

        return $menuItem;
    }

    public function update($data)
    {
        $menuItem = HeaderMenu::find($data['id']);
        $menuItem->url = $data['url'] ?? $menuItem->url;
        $menuItem->title_section = $data['title'] ?? $menuItem->title_section;
        $menuItem->save();

        return $menuItem;
    }
}