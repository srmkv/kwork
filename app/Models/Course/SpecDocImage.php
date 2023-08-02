<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecDocImage extends Model
{
    use HasFactory;

    protected $table = 'course_spec_doc_images';
    protected $guarded = [];
}
