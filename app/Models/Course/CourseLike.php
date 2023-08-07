<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLike extends Model
{
    use HasFactory;

    protected $table = 'course_like';
    protected $guarded = [];
    public $timestamps = false;

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
