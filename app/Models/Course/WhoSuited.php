<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhoSuited extends Model
{
    use HasFactory;


    public $table = 'text_block_who_suited';
    protected $guarded = [];
    public $timestamps = false;
}
