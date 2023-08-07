<?php

namespace App\Traits;

use App\Models\Course\Course;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait Path
{
    protected function simpleImagePath(String $img_name = null, $path = null)
    {
        if(!$img_name || !$path){
            return '';
        }
        return url('/') . Storage::url(config($path)) . '/' . $img_name;
    }
}