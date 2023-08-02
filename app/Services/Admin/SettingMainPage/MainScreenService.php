<?php
namespace App\Services\Admin\SettingMainPage;
use App\Models\SettingMainPage\MainScreen;
use App\Services\MainService;
use Illuminate\Support\Facades\Storage;

class MainScreenService
{   
    public function __construct()
    {
        $this->mainScreen = MainScreen::findOrFail(1);
    }

    public function updateText($data)
    {
        if(isset($data['title'])){
            $this->mainScreen->title = $data['title'];
        }
        if(isset($data['description'])){
            $this->mainScreen->description = $data['description'];
        }
        $this->mainScreen->save();
        return $this->mainScreen;
    }

    public function getMainScreen()
    {   
        return $this->mainScreen;
    }

    public function updateMedia($data)
    {   
        if(!$this->mainScreen->url == null){
            (new MainService)->deleteMedia(MainScreen::MAIN_SCREEN_PATH, basename($this->mainScreen->url));
        }

        $result = (new MainService)->addMedia(MainScreen::MAIN_SCREEN_PATH, $data['file']);
        $this->mainScreen->url = $result->first()['url'];
        $this->mainScreen->save();
        return $this->mainScreen;
    }

}