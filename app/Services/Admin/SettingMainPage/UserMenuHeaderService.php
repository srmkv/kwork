<?php
namespace App\Services\Admin\SettingMainPage;
use App\Models\SettingMainPage\BottomHeaderMenu;

class UserMenuHeaderService
{
    public function newItem($data)
    {
        $item = new BottomHeaderMenu;
        $item->type_user = $data['type_user'];
        $item->item_title = $data['item_title'] ?? '';
        $item->item_url = $data['item_url'] ?? '';
        $item->save();
        return $item;
    }

    public function updateItem($data)
    {
        $item = BottomHeaderMenu::find($data['id']);
        $item->item_url =  $data['item_title'] ?? $item->item_url;
        $item->item_title = $data['item_title'] ?? $item->item_title;
        $item->save();
        return $item;
    }

    public function removeItem($item_id)
    {
        $item = BottomHeaderMenu::find($item_id);
        if($item == null) {
            return response()->json([
                'message' => 'Такого элемента не существует..',
                'code' => 404
            ],404);
        } else {
            $item->delete();
            return response()->json([
                'message' => 'пункт меню успешно удален..',
                'code' => 200
            ], 200);
        }

    }

    public function getList()
    {
        $items = collect(BottomHeaderMenu::all());
        return $items->groupBy('type_user');
    }

    public function getListByType($type)
    {
        return BottomHeaderMenu::where('type_user', $type)->get();
    }
}