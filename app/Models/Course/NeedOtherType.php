<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class NeedOtherType extends Model
{
    use HasFactory;

    // public $timestamps = false;
    protected $guarded = [];
    
    protected $appends = [
        'required_types',
    ];



    protected function requiredTypes(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


}
