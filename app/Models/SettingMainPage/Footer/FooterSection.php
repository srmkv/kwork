<?php

namespace App\Models\SettingMainPage\Footer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSection extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany(FooterSectionItem::class);
    }

}
