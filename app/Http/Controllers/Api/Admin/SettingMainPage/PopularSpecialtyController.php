<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\SettingMainPage\PopularSpecialtyService;

class PopularSpecialtyController extends Controller
{
    public function __construct(PopularSpecialtyService $specialtyAction)
    {
        $this->specaialtyAction = $specialtyAction;
    }

    public function syncSpecialties(Request $request)
    {
        return $this->specaialtyAction->sync($request->all());
    }

    public function getSpecialties(Request $request)
    {
        return $this->specaialtyAction->getAll();
    }

    public function selectSpecialities(Request $request)
    {
        return $this->specaialtyAction->selectSpecialities();
    }
}
