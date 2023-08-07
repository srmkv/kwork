<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

    public $timestamps = false;

    protected $appends = [
    ];

    // не увидел связи эти , как будто старые?
    // public function pasports()
    // {
    //     return $this->hasMany(Passport::class);
    // }

    // public function snils()
    // {
    //     return $this->hasMany(Snils::class);
    // }
    
}
