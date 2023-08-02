<?php

namespace App\Models\Course;

use App\Models\Course\TagSearchCourse;
use App\Models\CourseRequiredDocument;
use App\Models\EduOrganization;
use App\Models\MediaLibrary;
use App\Services\CourseService;

use App\Models\SpecializedSecondaryEdu\SpecializedSecondarySpeciality;
use App\Models\HigherEdu\HigherEduSpeciality;
use App\Services\CatalogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;



class Course extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    const PATH_DOC_TAKE_IMG = 'media.path_doc_take_img';
    const PATH_IMG_SIMPLE = 'media.path_img_simple';
    const PATH_IMG_ANIMATE = 'media.path_img_animate';
    const PATH_VIDEO = 'media.path_video';
    const PATH_PHOTO = 'media.path_photo';
    const PATH_ICONS_PACKETS = 'media.path_icons_packets';
    const PATH_ICONS_WHOS = 'media.path_icons_whos';
    const PATH_ICONS_UTP = 'media.path_icons_utp';
    const PATH_PREVIEW = 'media.path_preview';
    const PATH_EDITOR = 'media.path_editor';
    const STATE_ACTIVE = 'Активен';

    const PRICE_DELIMETER = 100;


    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tree' => 'collection'
    ];

    protected $guarded = [];

    private $service;

    public function __construct()
    {
        $this->service = new CourseService();
    }


    public function duration()// FILTER
    {
       return $this->belongsToMany(StudyDuration::class, 'course_duration', 'course_id', 'study_duration_id');
    }

    public function studyForms()// FILTER
    {
       return $this->belongsToMany(StudyForm::class);
    }

    public function tagRefinements()// FILTER
    {
       return $this->belongsToMany(TagRefinement::class, 'course_tag_refinements');
    }

    public function categoryCourse()// FILTER
    {
        return $this->belongsToMany(CategoryCourse::class, 'course_relation_category');
    }

    public function getAllRedisProperties()
    {
        $result = collect();
        $categories = $this->categoryCourse->pluck('slug')->toArray();
        $result = $result->merge($categories)
            ->merge($this->addresses->pluck('slug')->toArray())
            ->merge($this->duration->pluck('slug')->toArray())
            ->merge($this->studyForms->pluck('slug')->toArray())
            ->merge($this->tagRefinements->pluck('slug')->toArray())
            ->unique()->toArray()
        ;
        return $result;
    }

    /**
     * Статус курса
     * Активен / Неактивен / На рассмотрении / Новый
     */
    public function state()
    {
        return $this->belongsTo(CourseState::class);
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'course_addresses_relation', 'course_id', 'address_id');
    }
    
    public function datesStudyStart()
    {
        return $this->belongsToMany(DateStudy::class, 'course_dates_study', 'course_id', 'date_study_id');
    }

    public function likes()
    {
        return $this->hasMany(CourseLike::class, 'course_id');
    }

    


    /**
     * Связи с документами курса
    */

    // образ. документы необходимые в курсе
    public function neededSpecialities() 
    {
        return $this->hasMany(NeededSpeciality::class, 'course_id');
    }

    // личные документы необходимые в курсе
    public function neededPersonalDocs() 
    {
        return $this->hasMany(NeededPersonalDoc::class, 'course_id');
    }

    // дополнительные документы необходимые в курсе
    public function needOtherTypes() 
    {
        return $this->hasMany(NeedOtherType::class, 'course_id');
    }


    /**
     * К-во вакантных мест
     * в спец разделе курса
     */

    public function vacantPlaceEduProgram()
    {
        return $this->hasOne(VacantPlaceEduProg::class);
    }
    
    public function vacantPlaceSpeciality()
    {
        return $this->hasOne(VacantPlacesSpeciality::class);
    }

    public function vacantPlaceDirection()
    {
        return $this->hasOne(VacantPlacesDirection::class);
    }

    public function vacantPlaceProfession()
    {
        return $this->hasOne(VacantPlacesProfession::class);
    }


    /**
     * Тип курса (форма обучения)
     * Очная / Дистанционная / Смешанная
     */
    public function type()
    {
        return $this->belongsTo(StudyForm::class, 'study_form_id', 'id');
    }

    public function specialities()
    {
        return $this->belongsToMany(Speciality::class);
    }

    public function useTechnologies()
    {
        return $this->belongsToMany(UseTechnology::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function docsTake()
    {
        return $this->hasMany(CourseDocTake::class);
    }

    public function studyPlanImages()
    {
        return $this->hasMany(CourseDocImage::class)->where('type', CourseDocImage::STUDY_PLAN_IMAGE_TYPE);
    }

    public function calendarStudyScheduleImages()
    {
        return $this->hasMany(CourseDocImage::class)->where('type', CourseDocImage::CALENDAR_STUDY_SHEDULE_IMAGE_TYPE);
    }

    public function specDocImages()
    {
        return $this->hasMany(CourseDocImage::class)->where('type', CourseDocImage::SPEC_DOC_IMAGE_TYPE);
    }

    

    public function studyDocs()
    {
        return $this->hasMany(StudyDoc::class);
    }

    

    public function banner()
    {
        return $this->belongsTo(Banner::class, 'id', 'course_id');
    }

    

    public function eduOrganizations()
    {
        return $this->belongsToMany(EduOrganization::class, 'course_edu_organizations');
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    public function medias()
    {
        return $this->morphMany(MediaLibrary::class, 'madiatable');
    }

    public function directions()
    {
        return $this->belongsToMany(Direction::class);
    }

    public function shoppingOffer()
    {
        return $this->hasMany(ShoppingOffer::class);
    }

    public function whoSuited()
    {
        return $this->hasMany(WhoSuited::class);
    }

    //потоки 
    public function flows()
    {
        return $this->hasMany(Flow::class);
    }

    public function comments()
    {
        return $this->hasMany(CourseComment::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    

    public function rating()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function ratingValue()
    {
        return $this->reviews()->avg('rating');
    }

    public function listPackets()
    {
        return $this->hasManyThrough(Packet::class, Flow::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    
    // в рудимменты, выпилить(!)
    public function docEduDirection()
    {
        return $this->hasMany(CourseRequiredEduDocument::class);
    }
    

    /**
     * Список документов необходимых для данного курса(личные/заменяющие)
     */
    public function docs()
    {
        return $this->hasMany(CourseRequiredDocument::class);
    }

    public function reviews()
    {
        return $this->hasMany(CourseReview::class)->where('parent_id', null);
    }

    

    public function levelsEducation()
    {
        return $this->belongsToMany(LevelEducation::class, 'course_level_education');
    }

    public function searchTags()
    {
        return $this->belongsToMany(TagSearchCourse::class, 'course_tag_search', 'course_id', 'tag_search_course_id');
    }

    public function scopeWithCategoryCourse($query)
    {
        $query->with('categoryCourse');
    }

    public function scopeWithWhoSuited($query)
    {
        $query->with('whoSuited');
    }

    public function scopeWithShoppingOffer($query)
    {
        $query->with('shoppingOffer');
    }

    public function scopeWithFlows($query)
    {
        $query->with('flows');
    }

    public function scopeWithState($query)
    {
        $query->with('state');
    }

    public function scopeWithDuration($query)
    {
        $query->with('duration');
    }

    public function scopeWithAddresses($query)
    {
        $query->with('addresses');
    }

    public function scopeWithTagRefinements($query)
    {
        $query->with('tagRefinements');
    }

    public function scopeWithStudyForms($query)
    {
        $query->with('studyForms');
    }

    public function scopeActive($query)
    {
        $query->where('state_id',1);
    }

    public function scopeActual($query)
    {
        $query->whereNotNull('min_price');
    }

    public function scopeNullable($query)
    {
        $query->whereNull('state_id');
    }

    public function scopePublished($query)
    {
        $query->where('is_published',1);
    }

}
