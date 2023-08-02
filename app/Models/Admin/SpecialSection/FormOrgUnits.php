<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Spatie\Image\Manipulations;


class FormOrgUnits extends Model  implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;


    public function docs()
    {
        return $this->hasMany(OrgUnitDoc::class, 'form_org_unit_id');
    }



    public function emails()
    {
        return $this->hasMany(OrgUnitEmail::class, 'form_org_unit_id');
    }

    public function sites()
    {
        return $this->hasMany(OrgUnitSite::class, 'form_org_unit_id');
    }



    public function registerMediaConversions(Media $media = null): void
    {
        // $this->addMediaConversion('thumb')
        //       ->width(368)
        //       ->height(232)
        //       ->sharpen(10);

        // пример сепии
        // $this->addMediaConversion('old-picture')
        //       ->sepia()
        //       ->border(10, 'black', Manipulations::BORDER_OVERLAY);
        
        // Обрезка или кадрирование изображения по центру для заданной ширины и высоты
        // $this->addMediaConversion('thumb-cropped')
        //     ->crop('crop-center', 400, 400);

        // не работает 
        // upd(2). работает , в очередях, временно не актуально, 
        // т.к превью уже вырываем в момент загрузки
        $this->addMediaConversion('pdf')
             ->width(300)
             ->height(200)
             ->pdfPageNumber(1);



    }





}
