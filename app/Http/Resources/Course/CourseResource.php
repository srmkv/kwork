<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\BannerResource;
use App\Http\Resources\CourseDocTakeResource;
use App\Http\Resources\CourseReviewResource;
use App\Http\Resources\DocWithReplacementResource;
use App\Http\Resources\FaqResource;
use App\Models\Course\CategoryCourse;
use App\Models\Course\Course;
use App\Models\Course\Flow;
use App\Models\Course\StudyDoc;
use App\Models\Course\WhoSuited;

use App\Models\HigherEdu\HigherEduSpeciality;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondarySpeciality;


use App\Services\CourseService;
use App\Services\FlowService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\Path;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;

class CourseResource extends JsonResource
{
    use Path;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   
        $emptyPlaces = collect([
            'budget_allocations_federal_budget' => 0,
            'budget_allocations_subject_rf_budget' => 0,
            'budget_allocations_local_budget' => 0,
            'budget_allocations_individ_business_budget' => 0
        ]);

        $emptyPlacesProfession = collect([
            'budget_allocations_federal_budget' => 0,
            'budget_allocations_subject_rf_budget' => 0,
            'budget_allocations_local_budget' => 0,
            'budget_allocations_individ_business_budget' => 0,
            'profession_id' => null
        ]);

        $mainCategories = [];
        $tree = [];
        if($this->tree){
            $tree = $this->tree;
            $mainCategoryIds = collect(json_decode($tree))->map(fn($item) => $item = $item[0]);
            $mainCategories = CategoryCourse::whereIn('id', $mainCategoryIds)->get(['id', 'title', 'slug', 'tag_id']);
        }

        $likeCourses = auth()->user()?->likeCourseIds();

        return [
            
            'id' => $this->id,
            'title' => $this->name,
            'slug' => $this->slug,
            'mainCategories' => $mainCategories,
            'like' => $likeCourses?->contains($this->id) ? 1 : 0,
            'rating' => $this->ratingValue(),
            'date_published' => $this->date_published,
            'max_price' => $this->max_price,
            'min_price' => $this->min_price,
            'date_min' => $this->date_min,
            'date_max' => $this->date_max,
            'start_dates' => $this->datesStudyStart->pluck('start'),
            'study_type' => $this->categoryCourse()->where('tag_id', 3)->get(),
            "flows" => FlowListResource::collection($this->filterFlows($this->flows, $request)),
            'state_id' => $this->state_id,
            "reviews" => CourseReviewResource::collection($this->reviews),
            'age_limit' => $this->age_limit,
            "banner_type_id" => $this->banner_type_id,
            "banner_color" => $this->banner_color,
            'banner_image' => $this->simpleImagePath($this->banner_image, Course::PATH_IMG_SIMPLE),
            'editor_image' => $this->simpleImagePath($this->editor_image, Course::PATH_EDITOR),
            'preview' => $this->simpleImagePath($this->preview, Course::PATH_PREVIEW),
            'video_type' => $this->video_type,
            'video_link' => $this->video_link,
            'video_file' => $this->simpleImagePath($this->video_file, Course::PATH_VIDEO),
            'is_doc_take' => $this->is_doc_take,
            'address' => $this->address,
            'count_places_non_residents' => $this->count_places_non_residents,
            'doc_take_title' => $this->doc_take_title,
            'doc_take_sub_title' => $this->doc_take_sub_title,
            'doc_take_description' => $this->doc_take_description,
            'is_restrict_block' => $this->is_restrict_block,
            'docs_take' => CourseDocTakeResource::collection($this->docsTake),
            'academic_hours' => $this->academic_hours,
            'academic_days' => $this->academic_days,
            'study_form' => $this->type ? $this->type->name : '',
            'comments_count' => $this->reviews->count(),
            'categories' => in_array($request->route()->getActionMethod(), ['catalog', 'getBySlug']) ? CatalogCategoryCourseResource::collection($this->categoryCourse) : $tree,
            //личные документы и их заменяющие
            'docs' => DocWithReplacementResource::collection($this->docs()->with('mainDocument', 'replacementDocuments')->get(['id', 'document_id', 'description'])->toArray()),
            //документы об образовании и их заменяющи
            'docs_edu' => $this->docEduDirection()->with('replaceSpecializedSpecialities', 'replaceHigherSpecialities')->get(),
            //вакантные места
            // 'vacant_place_edu_prog' => $empty_places,
            'vacant_place_edu_prog' => !empty($this->vacantPlaceEduProgram) ? collect($this->vacantPlaceEduProgram)->except(['id','course_id']) :  $emptyPlaces,
            'vacant_place_speciality' => !empty($this->vacantPlaceSpeciality) ? collect($this->vacantPlaceSpeciality)->except(['id','course_id']) :  $emptyPlaces,
            'vacant_place_direction' => !empty($this->vacantPlaceDirection) ? collect($this->vacantPlaceDirection)->except(['id','course_id']) :  $emptyPlaces,
            'vacant_place_profession' => !empty($this->vacantPlaceProfession) ? collect($this->vacantPlaceProfession)->except(['id','course_id']) :  $emptyPlacesProfession,

            'edu_organization' => EduOrganizationResource::collection($this->eduOrganizations),
            'shopping_offers' => TextBlockResource::collection($this->shoppingOffer),
            'who_suited' => WhoSuitedResouce::collection($this->whoSuited ),
            'tag_refinements' => $this->tagRefinements()->get(['tag_refinements.id','title']),
            'search_tags' => $this->searchTags,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'is_edu_doc_required' => $this->is_edu_doc_required,
            'is_by_doc_req' => $this->is_by_doc_req,
            'is_change_surname' => $this->is_change_surname,
            'flows_dates' => FlowService::nearestFlow($this->flows)->toArray(),
            'price' => $this->price,
            "faq_description" => $this->faq_description,
            "faqs" => FaqResource::collection($this->faqs),
            "title_programm" => $this->title_programm,
            "level_education_id" => $this->level_education_id,
            "description_study_programm" => $this->description_study_programm,
            "specialities" => $this->specialities,
            "directions" => $this->directions,
            "study_forms" => $this->studyForms,
            "use_technologies" => $this->useTechnologies,
            "teachers" => $this->teachers,
            "study_plan_images" => StudyPlanImagesResource::collection($this->studyPlanImages),
            "calendar_study_schedule_images" => StudyPlanImagesResource::collection($this->calendarStudyScheduleImages),
            "spec_doc_images" => StudyPlanImagesResource::collection($this->specDocImages),
            "study_docs" => $this->studyDocs,
        ];
    }

    private function filterFlows(Collection $flows, $request)
    {
        if($request->route()->getActionMethod() !== CourseService::TYPE_ACTION_CATALOG){
            return $flows;
        }

        return $flows->filter(fn($flow) => Carbon::make($flow->start) > Carbon::now()
             || $flow->type_id !== Flow::TYPE_GROUPS
        );
    }

    
}
