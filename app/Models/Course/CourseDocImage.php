<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDocImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    const DOC_TAKE_IMAGE_TYPE = 1;
    const STUDY_PLAN_IMAGE_TYPE = 2;
    const CALENDAR_STUDY_SHEDULE_IMAGE_TYPE = 3;
    const SPEC_DOC_IMAGE_TYPE = 4;

}
