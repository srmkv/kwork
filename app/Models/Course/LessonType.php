<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonType extends Model
{
    use HasFactory;

    public $table = 'lesson_types';
    protected $guarded = [];
    public $timestamps = false;

    public function lessons()
    {
        return $this->hasMany(CourseSectionLesson::class, 'type_id');
    }

}
