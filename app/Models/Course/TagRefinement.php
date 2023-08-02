<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Теги уточнения
 */
class TagRefinement extends Model
{
    use HasFactory;

    const FILTER = 'Особенность обучения';
    const FILTER_TITLE = 'Особенность курса';

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_tag_refinements');
    }
}
