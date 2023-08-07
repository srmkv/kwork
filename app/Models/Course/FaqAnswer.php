<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'faq_answers';
}
