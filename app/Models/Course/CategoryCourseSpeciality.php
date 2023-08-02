<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCourseSpeciality extends Model
{
    use HasFactory;

    protected $table = 'category_course_speciality';
    protected $guarded = [];
    public $timestamps = false;
    
    public function category()
    {
        return $this->belongsTo(CategoryCourse::class);
    }

    public function faqs()
    {
        return $this->hasMany(CategoryCourseSpecialityFaq::class, 'course_speciality_id');
    }

    public function levelEducation()
    {
        return $this->belongsTo(LevelEducation::class);
    }
}
