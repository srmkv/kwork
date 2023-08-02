<?php

namespace App\Models\SettingMainPage\Footer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterBottomLogo extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    const PATH_FOOTER_BOTTOM_LOGO = 'media.path_footer_bottom_logo';
}
