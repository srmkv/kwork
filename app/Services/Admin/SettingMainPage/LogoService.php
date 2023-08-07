<?php
namespace App\Services\Admin\SettingMainPage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Services\MainService;

use App\Models\SettingMainPage\Logo;

class LogoService
{
    public function uploadLogo($img, $type)
    {
        $result = (new MainService)->addMedia(Logo::LOGO_PATH, $img);
        $logo = Logo::updateOrCreate(
            ['type_logo' => $type],
            ['url' => $result->first()['url']]
        );
        return $logo;
    }
}