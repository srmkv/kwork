<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SettingMainPage\MainScreen;
use App\Services\Admin\SettingMainPage\MainScreenService;

use App\Http\Requests\Admin\MainScreenTextRequest;

class MainScreenController extends Controller
{
    public function __construct(MainScreenService $mainScreeAction)
    {
        $this->mainScreeAction = $mainScreeAction;
    }

    public function updateText(MainScreenTextRequest $request)
    {
        return $this->mainScreeAction->updateText($request->all());
    }

    public function getBlock()
    {
        return $this->mainScreeAction->getMainScreen();
    }

    public function uploadPicture(Request $request)
    {   
        return $this->mainScreeAction->updateMedia($request->all());
    }
}
