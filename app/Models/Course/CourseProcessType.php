<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProcessType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function courseProcess()
    {
        return $this->hasMany(CourseProcess::class, 'type_id');
    }
}
