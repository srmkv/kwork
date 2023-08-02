<?php

namespace App\Models\HigherEdu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\HigherEdu\HigherEduLevel;


class HigherEduLevel extends Model
{
    use HasFactory;


    public $table = 'level_education_higher';
    public $timestamps = false;



    public function HigherEduLevel()
    {
        return $this->hasMany(HigherEduLevel::class);
    }




}
