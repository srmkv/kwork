<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class FormAccesibleEnv extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;


    

    public function docs()
    {
        return $this->hasMany(AccessibleEnvDoc::class); 
    }
    

}
