<?php

namespace App\Models\Course;

use App\Models\DocEduDirection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelEducation extends Model
{
    use HasFactory;

    protected $hidden = [
      'created_at',
      'updated_at',
      'pivot',
    ];

    const FILTER = 'Уровень образования';

    // эта сущность сейчас участвует только в спец разделе? надо 
    // уточнить и выпилить всю эту кашу, уровни делятся на типы, а не одноранговая система
    
    public $table = 'level_education';

    public function specialities()
     {
       return $this->hasMany(Speciality::class);
     }

     public function diploms()
     {
        return $this->hasMany(DocEduDirection::class);
     }

     public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_level_education');
    }

    public static function slug()
    {
        return \Str::slug(self::FILTER);
    }


}
