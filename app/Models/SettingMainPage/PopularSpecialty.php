<?php

namespace App\Models\SettingMainPage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PopularSpecialty extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    protected $appends = [
        'specialties',
    ];


    protected function specialties(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
