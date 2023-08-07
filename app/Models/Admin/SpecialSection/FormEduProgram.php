<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class FormEduProgram extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;


    public function docs()
    {
        return $this->hasMany(EduProgramDoc::class);
    }


    //директора участвующие в этой программе
    public function directors()
    {   
        return $this->belongsToMany(FormDataDirectorEdu::class, 'edu_director_edu_program');

    }


}
