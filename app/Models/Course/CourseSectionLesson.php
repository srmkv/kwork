<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSectionLesson extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'course_section_lessons';
    
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'course_section_teachers', 'course_lesson_id')->withPivot('state_id');
    }

    public function themes()
    {
        return $this->hasMany(CourseSectionTheme::class, 'course_section_id');
    }

    public function type()
    {
        return $this->belongsTo(LessonType::class, 'type_id');
    }
}
