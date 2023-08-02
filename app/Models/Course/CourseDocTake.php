<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDocTake extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'course_docs_take';
     public $timestamps = false;

    public function images()
    {
        return $this->hasMany(DocTakeImage::class, 'doc_take_id', 'id');
    }
}
