<?php

namespace App\Models\HigherEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HigherBackelorDirection extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function specialities()
    {
        return $this->hasMany(HigherBackelorSpeciality::class,'direction_id');
    }
}
