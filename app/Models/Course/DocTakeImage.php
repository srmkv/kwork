<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocTakeImage extends Model
{
    use HasFactory;

    protected $table = 'course_doc_take_images';
    protected $guarded = [];
    public $timestamps = false;

}
