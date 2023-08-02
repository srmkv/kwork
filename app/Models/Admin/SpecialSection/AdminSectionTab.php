<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSectionTab extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    // эта модель отвечает за "вкладку", которую можно 
    // прикрепить к разделу, реализована после всего остального функционала
    
    public function spoilers()
    {
        return $this->hasMany(SectionSpoiler::class)->orderBy('position');
    }
}
