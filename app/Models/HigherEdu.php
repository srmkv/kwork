<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HigherEdu extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $table = 'higher_education';

    protected $guarded = [];

    protected $appends = [
        'status_doc'
    ];

    protected function statusDoc(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
