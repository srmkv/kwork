<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function answer()
    {
        return $this->belongsTo(FaqAnswer::class, 'id', 'faq_question_id');
    }
}
