<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Packet extends Model
{
    use HasFactory;

    protected $hidden = [
        'laravel_through_key'
    ];

    protected $appends = [
        'split_months',
    ];

    protected $guarded = [];

    protected function splitMonths(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


    // не актуально, выпилить
    public function getPrice() 
    {
        return $this->belongsTo(Price::class, 'price_id');
    }
    // не актуально, выпилить
    public function descriptions()
    {
        return $this->hasMany(PacketDescription::class);
    }


    public function saleRules()
    {
        return $this->hasMany(PacketSaleRule::class);
    }

    
}
