<?php

namespace App\Models\Course;

use App\Events\BidCreatedEvent;
use App\Http\Resources\Course\EduOrganizationResource;
use App\Models\EduOrganization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $table = 'bids';
    protected $dates = [
        'created_at',
        'updated_at',
        'pay_date',
    ];

    protected $fillable = [
        'edu_organization_id',
        'price', 
        'pay_method_id', 
        'autor_id', 
        'paid',
        'state_id'
    ];


    public function notifications()
    {
        return $this->morphMany(Notify::class, 'notifytable');
    }

    public function clients()
    {
        return $this->belongsToMany(User::class, 'bid_user');
    }

    public function payMethod()
    {
        return $this->belongsTo(PayMethod::class, 'pay_method_id');
    }

    public function courses()
    {
        return $this->hasMany(BidCourse::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function state()
    {
        return $this->belongsTo(BidState::class, 'state_id');
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function flow()
    {
        return $this->belongsTo(Flow::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'bid_course_users', 'bid_course_id', 'user_id');
    }

    

    
}
