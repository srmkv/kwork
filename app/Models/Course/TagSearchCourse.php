<?php
namespace App\Models\Course;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagSearchCourse extends Model
{
    use HasFactory;

    protected $table = 'tag_search_courses';
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_tag_search');
    }
}

