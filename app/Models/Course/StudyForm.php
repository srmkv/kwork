<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyForm extends Model
{
    use HasFactory;
    const FILTER = 'Форма обучения';

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
