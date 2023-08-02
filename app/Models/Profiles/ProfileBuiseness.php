<?php

namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use App\Models\Company;
use App\Models\Employee;

use Illuminate\Database\Eloquent\Relations\Relation;

class ProfileBuiseness extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $guarded = [];

    public $table = 'profiles_buiseness';



     




    public function employees()
    {
        return $this->hasMany(Employee::class, 'business_id', 'employee_id', 'business_employee');
    }


    //test
    // public function company()
    // {
    //     return $this->morphTo();
    // }


    // public function employeesBusinsess()
    // {
    //    return $this->morphToMany(Employee::class, 'employeeble');
    // }



    // public function employeesBusinsess()
    // {
    //    return $this->morphToMany(Employee::class, 'employeeble');
    // }

    public function employeesBusinsess()
    {  
       Relation::enforceMorphMap([
           'business' => 'App\Models\Profiles\ProfileBuiseness',
           'ip' => 'App\Models\Profiles\ProfileSelfEmployed',
       ]);
       

       return $this->morphToMany(ProfileIndividual::class, 'profiles_individuals', 'profilebles', 'profileble_id', 'profile_id')
             ->withPivot([
                'status',
                'role_id',
                'profile_who_invited',
                'invitation_date',
                'job_position_who'
            ]);
    }
}
