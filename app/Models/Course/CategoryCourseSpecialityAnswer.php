<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCourseSpecialityAnswer extends Model
{
    use HasFactory;

    protected $table = 'category_course_speciality_faq_answers';
    protected $guarded = [];
}
