<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Snils extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public $table = 'snils';

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];


    protected function statusDoc(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    
}
