<?php

namespace App\Models\Course;

use App\Models\DocEduDirection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SpecializedSecondaryEdu\SpecializedSecondarySpeciality;
use App\Models\HigherEdu\HigherEduSpeciality;

/**
 * Основной обр документ
 */
class CourseRequiredEduDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'course_id',
        'description',
        'type_edu'
    ];
    public $timestamps = false;
    


    public function mainDocument()
    {
        return $this->belongsTo(DocEduDirection::class, 'document_id');
    }

    public function replacementDocuments()
    {
        return $this->belongsToMany(DocEduDirection::class, 'course_edu_docs_replacement',  'doc_edu_direction_id', 'document_id')->withPivot('course_id');
    }




    public function replaceSpecializedSpecialities()
    {
       return $this->belongsToMany(
            SpecializedSecondarySpeciality::class, 
            'required_replace_specialized_edu',
            'document_edu_id',
            'specialized_secondary_speciality_id'
        );
    }



    public function replaceHigherSpecialities()
    {
        return $this->belongsToMany(
            HigherEduSpeciality::class,
            'required_replace_higher_edu',
            'document_edu_id',
            'higher_edu_speciality_id'

        );
    }






}
