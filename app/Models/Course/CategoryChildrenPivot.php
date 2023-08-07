<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryChildrenPivot extends Pivot
{
    use HasFactory;

    public $table = 'category_course_parent_child';

    protected $guarded = [];

    protected $casts = [
        'tree' => 'string'
    ];
}
