<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    use HasFactory;

    const TYPE_GROUPS = 1;
    const TYPE_AFTER_BY = 2;

    protected $hidden = [
        'course_id',
        'study_form_id',
    ];

    protected $fillable = [
        'title',
        'start',
        'end',
        'study_form_id',
        'type_id',
        'course_id',
        'days_after_by',
    ];

    public $timestamps = false;

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function packets()
    {
        return $this->hasMany(Packet::class);
    }

    public function type()
    {
        return $this->belongsTo(FlowType::class, 'type_id');
    }

    

}
