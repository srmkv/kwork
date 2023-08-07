<?php

namespace App\Models\SettingMainPage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Contact extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    protected $attributes = [
            'main_contacts' => '{
                "mail": null,
                "phones": []
            }'
    ];

    protected $appends = [
        'main_contacts'
    ];


    protected function mainContacts(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


}
