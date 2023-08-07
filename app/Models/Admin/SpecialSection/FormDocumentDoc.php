<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FormDocumentDoc extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;

    public $timestamps = false;
}
