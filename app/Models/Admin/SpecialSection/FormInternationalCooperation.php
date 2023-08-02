<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FormInternationalCooperation extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;


    const COOPERATION_INTER_IMAGES = 'media.path_coopertation_inter';


    public function images()
    {
        return $this->hasMany(InternationalCooperationImage::class);    
    }

}
