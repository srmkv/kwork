<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocEduDirection extends Model
{
    use HasFactory;

    protected $table = 'doc_edu_direction';
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
        'level_education_id',
    ];
    
}
