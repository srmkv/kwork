<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateStudy extends Model
{
    use HasFactory;

    const FILTER = 'Дата курса';

    protected $table = 'dates_study';
    protected $guarded = [];
    public $timestamps = false;

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_dates_study', 'date_study_id', 'course_id');
    }
}
