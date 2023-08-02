<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileSelfEmployed;



class Company extends Model
{
    use HasFactory;


    public function businessProfiles()
    {
        return $this->morphMany(ProfileBuiseness::class, 'company');
    }


    public function ProfileSelfEmployed()
    {
        return $this->morphMany(ProfileSelfEmployed::class, 'company');
    }

}
