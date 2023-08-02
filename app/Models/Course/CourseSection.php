<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Раздел в потке, при создании курса
 */
class CourseSection extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'date',
        'hours',
        'flow_id',
        'study_form_id',
        'address',
    ];

    public $table = 'course_sections';

    public function getExpert()
    {
        return $this->belongsTo(Expert::class, 'expert_id');
    }

    public function lessons()
    {
        return $this->hasMany(CourseSectionLesson::class, 'course_section_id');
    }

    


}
