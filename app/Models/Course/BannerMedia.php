<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerMedia extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [
        'content_type_id'
    ];

    public $timestamps = false;
}
