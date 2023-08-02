<?php

namespace App\Models\Course;

use App\Models\Filter\FilterCategoryTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function filter()
    {
        return $this->belongsTo(FilterCategoryTag::class, 'tag_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryCourse::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(CategoryCourse::class, 'sub_category_id');
    }
}
