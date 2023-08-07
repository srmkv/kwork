<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileSelfEmployed;
use App\Models\Profiles\ProfileIndividual;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'user_employees';
    protected $guarded = [];


    public function companies()
    {
        
        return $this->belongsToMany(ProfileBuiseness::class, 'business_employee','employee_id', 'business_id')
                    ->withPivot([
                        'status',
                        'user_who_invited',
                        'invitation_date',
                        'job_position_who'
                    ]);
    }


    public function profile()
    {
        return $this->BelongsTo(ProfileIndividual::class, 'individual_profile_id');
    }




    public function companiesBusiness()
    {
        return $this->morphedByMany(ProfileBuiseness::class, 'employeeble');
    }
    

    public function companiesIp()
    {
        return $this->morphedByMany(ProfileSelfEmployed::class, 'employeeble');
    }

}
