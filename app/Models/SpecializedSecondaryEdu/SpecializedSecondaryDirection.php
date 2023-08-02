<?php

namespace App\Models\SpecializedSecondaryEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecializedSecondaryDirection extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function specialities()
    {
        return $this->hasMany(SpecializedSecondarySpeciality::class,'direction_id');
    }

}
