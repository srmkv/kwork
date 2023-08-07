<?php

namespace App\Models;

use App\Models\Course\Course;
use App\Models\Course\CourseProcess;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRolesAndPermissions; 
use App\Models\Order\AdmissionDocument;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Profiles\ProfileSelfEmployed;
use App\Models\Profiles\ProfileBuiseness;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Chat\Message;
use App\Models\Chat\ChatRoom;
use App\Models\Payment\PaymentCard;
use App\Models\Course\CourseLike;

class User extends Authenticatable implements HasMedia
{   

    use HasApiTokens,
        HasFactory,
        Notifiable,
        InteractsWithMedia, 
        HasRolesAndPermissions;

    const AVATAR_PATH = 'media.path_avatars';

    protected $fillable = [
        'name',
        'phone',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function mail()
    {
        return $this->hasOne(Email::class);
    }

    public function individualProfile()
    {
        return $this->hasOne(ProfileIndividual::class);
    }

    // тут и ип и самозанятые ( по одному )
    public function selfEmployedProfile()
    {
        return $this->hasMany(ProfileSelfEmployed::class);
    }

    public function likeCourseIds()
    {
        return CourseLike::where('user_id', $this->id)->get(['course_id'])->pluck('course_id');
    }

    public function ProfileBuiseness() //очепятка(
    {
        return $this->hasMany(ProfileBuiseness::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function passports()
    {
        return $this->belongsToMany(Passport::class);
    }

    public function snils()
    {
        return $this->hasMany(Snils::class, 'user_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'admin_id');
    }
    
    public function employmentHistory()
    {
        return $this->hasMany(EmploymentHistory::class);
    }

    public function otherDocuments()
    {
        return $this->hasMany(OtherDoc::class);
    }

    public function nameReplacements()
    {
        return $this->hasMany(Replacename::class);
    }

    // среднее обр.
    public function secondaryEdu()
    {
        return $this->hasMany(SecondaryEdu::class);
    }

    // высшее обр.
    public function higherDiploms()
    {
        return $this->hasMany(HigherEdu::class);
    }

    // документы на зачисление(в заявке)
    public function admissionDocuments()
    {
        return $this->hasMany(AdmissionDocument::class);
    }

    public function withTokens()
    {
        return $this->belongsTo(DevToken::class, 'id','user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function pin()
    {
        return $this->belongsTo(Pin::class, 'id','user_id');
    }

    public function courseProcesses()
    {
        return $this->hasMany(CourseProcess::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    // ЧАТЫ
    
    public function unreadMessages()
    {
        return $this->belongsToMany(Message::class)->wherePivot('read_at', null);
    }

    public function chats()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_user');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function paymentCards() 
    {
        return $this->hasMany(PaymentCard::class);
    }


}

