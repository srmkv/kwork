<?php

namespace App\Models\Course;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime',
    ];

    public function childs()
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id') ;
    }

    // подкатегории рекурсивно
    public function childsRecursive()
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id')->with('childs')
            // ->where('is_published', 1)
        ;
    }

    public function remove()
    {
        $this->childs()->delete();
        $this->childsRecursive()->delete();
        $this->delete();
        return true;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function scopeWithoutParent()
    {
        return self::whereNull('parent_id');
    }

}
