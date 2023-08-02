<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacketDescription extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
