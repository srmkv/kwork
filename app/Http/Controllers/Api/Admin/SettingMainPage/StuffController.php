<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\SettingMainPage\StuffService;

class StuffController extends Controller
{
    public function __construct(StuffService $stuffAction)
    {
        $this->stuffAction = $stuffAction;
    }

    public function getStuffs(Request $request)
    {
        return $this->stuffAction->all();
    }

    public function updateSubtitle(Request $request)
    {   
        return $this->stuffAction->updateSubtitle($request->text);
    }

    public function createStuff(Request $request)
    {
        return $this->stuffAction->create($request->only('fio', 'job_position', 'sort'));
    }

    public function updateStuff(Request $request, $id)
    {
        return $this->stuffAction->update($request->only('fio', 'job_position', 'sort'), $id);
    }

    public function updatePhotoStuff(Request $request, $id)
    {
        return $this->stuffAction->updatePhoto($request->file('file'), $id);
    }

    public function deletePhotoStuff(Request $request, $id)
    {
        return $this->stuffAction->deletePhotoStuff($id);
    }

    public function deleteStuff(Request $request, $id)
    {
        return $this->stuffAction->delete($id);
    }
}
