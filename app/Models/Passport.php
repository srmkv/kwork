<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Passport extends Model implements HasMedia
{   
    use InteractsWithMedia;
    use HasFactory;

    public $table = 'passports';

    protected $guarded = [];
    protected $appends = [
        'fullname',
        'status_doc'
    ];

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


    protected function fullname(): Attribute
    {
        return new Attribute(fn ($value, $attributes) => join(' ', [$this->first_name, $this->last_name, $this->middle_name]));
    }
}
