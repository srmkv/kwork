<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityPlanYear extends Model
{
    use HasFactory;


    public function sections()
    {
        // return $this->hasMany(PlanYearDocument::class);// вот это неправильно

        return $this->hasMany(SectionYearDocument::class); // это гуд
        
    }


}
