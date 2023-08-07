<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    const FILTER = 'Место проведения';

    protected $table = 'course_addresses';
    protected $guarded = [];
    public $timestamps = false;

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_addresses_relation', 'address_id', 'course_id');
    }
}
