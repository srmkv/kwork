<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacantPlaceEduProg extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    protected $table = 'vacant_places_edu_progs';
}
