<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SettingMainPage\MainShoppingOffer;

use App\Services\Admin\SettingMainPage\MainShoppingOfferService;


class MainShoppingOfferController extends Controller
{
    public function __construct(MainShoppingOfferService $offerActon)
    {
        $this->offerActon = $offerActon;
    }

    public function createUtp(Request $request)
    {
        return $this->offerActon->create($request->all());
    }

    public function editUtp(Request $request, $id)
    {
        return $this->offerActon->edit($request->only(['description', 'icon', 'icon_color']), $id);
    }

    public function deleteUtp(Request $request, $id)
    {
        return $this->offerActon->delete($id);
    }

    public function getAllUtp(Request $request)
    {
        return $this->offerActon->all();
    }


}
