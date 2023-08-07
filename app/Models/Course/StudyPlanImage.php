<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyPlanImage extends Model
{
    use HasFactory;

    protected $table = 'course_study_plan_images';
    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
        'course_id',
    ];
    
}
