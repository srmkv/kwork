<?php

namespace App\Models\Profiles;

use App\Models\User;
use App\Traits\Path;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Relation;
 
// Relation::enforceMorphMap([
//     'business' => 'App\Models\Profiles\ProfileBuiseness',
//     'self_employed' => 'App\Models\Profiles\ProfileSelfEmployed',
// ]);




class ProfileIndividual extends Model
{
    use Path;

    protected $table = 'profiles_individuals'; 
    protected $appends = [
        'avatarimage'
    ];
    
    protected $fillable = [
        'user_id',
        'avatar', 
        'lastname',
        'name',
        'middle_name', 
        'phone',
        'email',
        'date_birthday',
        'job_position' 
    ];



    public function avatarimage(): Attribute
    {
        return new Attribute(fn () => $this->simpleImagePath($this->avatar, User::AVATAR_PATH));
    }

    

    // обратная связь не работает, временно в сервисах
    public function profilesBuisiness()
    {
        return $this->morphedByMany(ProfileBuiseness::class,

            'profiles_individualsable',
            'profilebles',
            'profileble_id',
            // '',

        );
    }
    

    public function companiesIp()
    {
        return $this->morphedByMany(ProfileSelfEmployed::class,
        'profiles_buiseness',
        'profilebles'
        );
    }



    
}
