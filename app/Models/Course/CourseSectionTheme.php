<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSectionTheme extends Model
{
    use HasFactory;
    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $guarded = [];
    protected $table = 'course_section_themes';
    
}
