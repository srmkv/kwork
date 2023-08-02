<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class NeededPersonalDoc extends Model
{
    use HasFactory;
    
    // public $timestamps = false;
    protected $guarded = [];

    protected $appends = [
        'required_docs',
        'other_type_docs'
    ];

    protected function requiredDocs(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


    protected function otherTypeDocs(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }




}
