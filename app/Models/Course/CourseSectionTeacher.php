<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для связной таблицы между разделом потока и преподавателем, так как 
 * содержит pivot поле через которое осуществляется связь со статусом преподавателя
 * в контексте конкретного раздела
 */
class CourseSectionTeacher extends Model
{
    use HasFactory;

    protected $table = 'course_section_teachers';
    protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(TeacherState::class, 'state_id');
    }
}
