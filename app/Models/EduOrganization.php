<?php

namespace App\Models;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EduOrganization extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class)->where('state_id', 1);
    }
}
