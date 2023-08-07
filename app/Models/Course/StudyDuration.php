<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyDuration extends Model
{
    use HasFactory;

    const FILTER = 'Длительность обучения';

    protected $table = 'study_duration';
    protected $guarded = [];
    public $timestamps = false;

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_duration', 'study_duration_id', 'course_id');
    }

}
