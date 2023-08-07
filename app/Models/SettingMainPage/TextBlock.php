<?php

namespace App\Models\SettingMainPage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextBlock extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    const TEXT_BLOCK_PATH = 'media.path_text_blocks';
}
