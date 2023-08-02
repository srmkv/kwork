<?php

namespace App\Traits;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait Slug
{
    protected static function getSlug(string $data)
    {
        return \Str::slug($data);
    }
}