<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class EmploymentHistory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    protected $appends = [
        'media_ids',
    ];

    protected function mediaIds(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    
}
