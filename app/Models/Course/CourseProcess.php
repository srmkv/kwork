<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProcess extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(CourseProcessType::class);
    }
}
