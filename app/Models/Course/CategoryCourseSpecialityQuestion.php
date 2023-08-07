<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCourseSpecialityQuestion extends Model
{
    use HasFactory;

    protected $table = 'category_course_speciality_faq_questions';
    protected $guarded = [];

    public function answer()
    {
        return $this->belongsTo(CategoryCourseSpecialityAnswer::class, 'id', 'faq_question_id');
    }
}
