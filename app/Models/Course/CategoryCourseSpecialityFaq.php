<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCourseSpecialityFaq extends Model
{
    use HasFactory;

    protected $table = 'category_course_speciality_faqs';
    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(CategoryCourseSpecialityQuestion::class);
    }
}
