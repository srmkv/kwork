<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Admin\UploadLogoRequest;

use App\Services\Admin\SettingMainPage\LogoService;

class LogoController extends Controller
{   
    public function __construct(LogoService $logo)
    {
        $this->logo = $logo;
    }

    public function uploadLogo(UploadLogoRequest $request)
    {
        return $this->logo->uploadLogo($request->file('file'), $request->type);
    }
}
