<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_teacher');
    }

    public function courseSections()
    {
        return $this->belongsToMany(CourseSection::class, 'course_section_teachers');
    }

    public function state()
    {
        return $this->hasOneThrough(TeacherState::class, CourseSectionTeacher::class, 'state_id', 'id');
    }

    public function degree()
    {
        return $this->belongsTo(AcademicDegree::class, 'degree_id');
    }
}
