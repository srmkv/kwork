<?php

namespace App\Models\SettingMainPage\Footer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterTopLogo extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    const PATH_FOOTER_TOP_LOGO = 'media.path_footer_top_logo';
}
