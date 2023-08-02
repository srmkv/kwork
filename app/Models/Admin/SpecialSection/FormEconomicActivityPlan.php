<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormEconomicActivityPlan extends Model
{
    use HasFactory;

    public function years()
    {
        return $this->hasMany(ActivityPlanYear::class);
    }


}
