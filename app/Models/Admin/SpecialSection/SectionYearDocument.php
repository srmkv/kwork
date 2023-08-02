<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Spatie\Image\Manipulations;

class SectionYearDocument extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;

    public $timestamps = false;


    public function docs()
    {
        return $this->hasMany(SectionYearDoc::class);
    }

}
