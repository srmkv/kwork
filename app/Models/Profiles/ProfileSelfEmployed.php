<?php

namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Relation;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use App\Models\Employee;


class ProfileSelfEmployed extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'profiles_self_employed';
    protected $guarded = [];

    // public function employeesBusinsess()
    // {
    //    return $this->morphToMany(ProfileIndividual::class, 'profiles_individuals', 'profilebles', 'profileble_id', 'profile_id');
    // }


   public function employeesBusinsess()
   {  

      Relation::enforceMorphMap([
          'ip' => 'App\Models\Profiles\ProfileSelfEmployed',
      ]);


      return $this->morphToMany(ProfileIndividual::class,
         'profiles_individuals',
         'profilebles',
         'profileble_id',
         'profile_id'
      )->withPivot([
         'status',
         'role_id',
         'profile_who_invited',
         'invitation_date',
         'job_position_who',
         'job_position_employee'
      ]);
   
   }

    

}
