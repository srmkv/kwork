<?php

namespace App\Models\SettingMainPage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextBottomBlock extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    const PATH_BOTTOM_BLOCK = 'media.path_text_bottom_block';
}
