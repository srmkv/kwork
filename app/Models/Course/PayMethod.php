<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayMethod extends Model
{
    use HasFactory;

    protected $table = 'pay_methods';
    protected $fillable = [
        'name'
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
