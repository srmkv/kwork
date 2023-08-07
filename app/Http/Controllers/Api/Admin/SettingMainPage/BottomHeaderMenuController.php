<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Admin\UserMenuItemRequest;
use App\Services\Admin\SettingMainPage\UserMenuHeaderService;

class BottomHeaderMenuController extends Controller
{   
    public function __construct(UserMenuHeaderService $userMenu)
    {
        $this->userMenu = $userMenu;
    }

    public function createItem(UserMenuItemRequest $request)
    {
        return $this->userMenu->newItem($request->all());
    }

    public function editItem(Request $request)
    {
        return $this->userMenu->updateItem($request->all());
    }

    public function deleteItem(Request $request, $item_id)
    {
        return $this->userMenu->removeItem($item_id);
    }

    public function getUserMenu(Request $request)
    {   
        if($request->type_user == 'all') {
            return $this->userMenu->getList();
        } else {
            return $this->userMenu->getListByType($request->type_user);
        }   
    }
}
