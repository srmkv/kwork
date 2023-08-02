<?php

namespace App\Models\SettingMainPage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainScreen extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    const MAIN_SCREEN_PATH = 'media.path_main_screen';
}
