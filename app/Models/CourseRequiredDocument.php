<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Основной документ для создания курса, для которого могут быть заменяющие документы
 */
class CourseRequiredDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'course_id',
        'description',
    ];
    public $timestamps = false;
    


    public function mainDocument()
    {
        return $this->belongsTo(Doc::class, 'document_id');
    }

    public function replacementDocuments()
    {
        return $this->belongsToMany(Doc::class, 'document_course_required_documents',  'course_required_document_id', 'document_id')->withPivot('course_id');
    }

    
}
