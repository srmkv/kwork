<?php

namespace App\Models\Course;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidCourse extends Model
{
    use HasFactory;

    protected $table = 'bid_course';

    protected $fillable = [
        'flow_id',
        'packet_id',
        'course_id',
        'bid_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
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
