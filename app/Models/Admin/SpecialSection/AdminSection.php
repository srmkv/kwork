<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use App\Models\Admin\SpecialSection\Through\AdminSectionDataOrg;


class AdminSection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function spoilers()
    {
        return $this->hasMany(SectionSpoiler::class)->orderBy('position');
    }


    public function tabs()
    {
        return $this->hasMany(AdminSectionTab::class);
    }



}
