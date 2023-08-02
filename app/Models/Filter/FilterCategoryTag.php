<?php

namespace App\Models\Filter;

use App\Models\Course\CategoryCourse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterCategoryTag extends Model
{   

    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
    ];

    public $table = 'filter_category_tag';
    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany(CategoryCourse::class, 'tag_id');
    }

    public function scopeWithCategories($query)
    {
        $query->with('categories');
    }

    public function scopeMenu($query)
    {
        $query->where('menu', 1);
    }

    public function scopeStudyTypes($query)
    {
        $query->where('tag_id', 3);
    }
}
