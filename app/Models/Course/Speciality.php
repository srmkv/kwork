<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    use HasFactory;

    public $table = 'specialities';
    public $timestamps = false;

    protected $hidden = [
      'created_at',
      'updated_at',
      'level_education_id',
      'pivot',
    ];

    protected $fillable = [
      'title',
      'description'
    ];

    // все курсы по специальности

    public function courses()
    {
      return $this->belongsToMany(Course::class);
    }

    // полиморф не нужен?
    // public function getEducationLevel()
    // {
    //     return $this->morphMany(LevelEducation::class, 'levelEd');
    // }



    public function levelEducation()
    {
      
      return $this->belongsTo(LevelEducation::class, 'level_education_id');

    }



    
}
