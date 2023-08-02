<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\User;

class ChatRoom extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
    protected $appends = [
        'author',
        'profiles'
    ];

    protected function author(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    protected function profiles(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }


}
