<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Filter\FilterCategoryTag;


class CategoryCourse extends Model
{
    use HasFactory;

    const FILTER = 'Категория';
    const SPECIALITY_FILTER_IDS = [1,4]; // НАПРАВЛЕНИЕ / СПЕЦИАЛЬНОСТЬ
    const PATH_IMG = 'media.path_img_category';
    const TABLE = 'course_categories';
    public const RESOURCE_NAME = 'course_categories';
    public $table = 'course_categories';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $guarded = [];

    protected $appends = [
        'count_courses',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_relation_category');
    }

    public function getCountCoursesAttribute()
    {
        $categoryCourseIds = self::where('slug', $this->slug)->get(['id'])->pluck('id')->toArray();
        return \DB::table('course_relation_category')->whereIn('category_course_id', $categoryCourseIds)->count();
    }

    /**
     * получим подкатегории
     */
    public function parent()
    {
        return $this->hasMany(__CLASS__, 'id', 'parent_id')->with('children');
    }

    /**
     * получим подкатегории
     */
    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id')->with('children');
    }

    public function childrenWithSelfTag()
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id')->where('tag_id', $this->tag_id);
    }

    public function speciality()
    {
        return $this->belongsTo(CategoryCourseSpeciality::class, 'id', 'category_course_id');
    }

    // Статические методы
    public static function napravlenies()
    {
        return self::whereNull('tree')->get();
    }

    // фильтр для поиска
    public function filterTag() 
    {
        return $this->belongsTo( FilterCategoryTag::class, 'tag_id');
    }

    public function allChildrens()
    {
        return self::where('tree', 'like', '%[' . $this->id . ']%')
            ->orWhere('tree', 'like', '%[' . $this->id . ',%')
            ->orWhere('tree', 'like', '%,' . $this->id . ',%')
            ->orWhere('tree', 'like', '%,' . $this->id . ']%')
        ;
    }

    public function allParents()
    {
        return json_decode($this->tree);
    }

    public function scopeWithParents($query)
    {
        $query->with('parent');
    }

    public function scopeWithChildren($query)
    {
        $query->with('children');
    }

    public function scopeWithFilterTag($query)
    {
        $query->with('filterTag');
    }

    public function scopeWithCourses($query)
    {
        $query->with('courses');
    }

    public function scopeLikeMainParentIds($query, $id)
    {
        $query->where('main_parent_ids', 'like', '%[' . $id . ']%')
        ->orWhere('main_parent_ids', 'like', '%[' . $id . ',%')
        ->orWhere('main_parent_ids', 'like', '%,' . $id . ',%')
        ->orWhere('main_parent_ids', 'like', '%,' . $id . ']%');
    }

    public function scopeActive($query)
    {
        $query->where('status',1);
    }

    public function scopeStudyTypes($query)
    {
        $query->where('tag_id', 3);
    }

    public function scopeByStudyTypes($query, array $ids)
    {
        if(count($ids)){
            $query->where('tree', 'like', '%[' . $ids[0] . ']%');
            foreach($ids as $id){
                $query = $query->orWhere('tree', 'like', '%[' . $id . ',%')
                ->orWhere('tree', 'like', '%,' . $id . ',%')
                ->orWhere('tree', 'like', '%,' . $id . ']%');
            }
        }
    }

    public function scopeSpecialities($query)
    {
        return $this->where('tag_id', 4);
    }

    public function scopeEducationTypes()
    {
        return self::where('tag_id', 3);
    }

    public function scopeMainCategories($query)
    {
        return self::whereNull('parent_id');
    }

    public function scopeMainCategoriesSlugs($query)
    {
        return self::mainCategories()->get(['slug'])->pluck('slug');
    }
}


 