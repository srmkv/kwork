<?php

namespace App\Models\SettingMainPage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stuff extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STUFF_PHOTO_PATH = 'media.path_photo_stuff';
}
