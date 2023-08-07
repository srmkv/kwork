<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherState extends Model
{
    use HasFactory;

    protected $table = 'teacher_states';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'course_section_teachers');
    }
}
