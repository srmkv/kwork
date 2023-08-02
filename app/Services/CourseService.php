<?php

namespace App\Services;

use App\Http\Requests\AddPreviewRequest;
use App\Http\Requests\CourseDocTakeStoreRequest;
use App\Http\Requests\CourseReview\CourseReviewStoreRequest;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseVideoDeleteRequest;
use App\Http\Requests\CourseVideoStoreRequest;
use App\Http\Requests\DeleteRequiredDocumentRequest;
use App\Http\Requests\DeleteRequiredEduDocumentRequest;
use App\Http\Requests\FaqDeleteRequest;
use App\Http\Requests\PreviewDeleteRequest;
use App\Http\Resources\Course\CategoryCourseResource;
use App\Http\Resources\Course\CategoryCourseShortResource;
use App\Http\Resources\DocResource;
use App\Models\Course\Address;
use App\Models\Course\BannerContentType;
use App\Models\Course\BannerType;
use App\Models\Course\CategoryCourse;
use App\Models\Course\Course;
use App\Models\Course\CourseDocImage;
use App\Models\Course\CourseDocTake;
use App\Models\Course\CourseProcessType;
use App\Models\Course\CourseRequiredEduDocument;
use App\Models\Course\CourseReview;
use App\Models\Course\CourseSection;
use App\Models\Course\CourseSectionLesson;
use App\Models\Course\CourseState;
use App\Models\Course\DateStudy;
use App\Models\Course\Direction;
use App\Models\Course\DocTakeImage;
use App\Models\Course\Faq;
use App\Models\Course\Flow;
use App\Models\Course\FlowType;
use App\Models\Course\LessonType;
use App\Models\Course\LevelEducation;
use App\Models\Course\Packet;
use App\Models\Course\Price;
use App\Models\Course\ShoppingOffer;
use App\Models\Course\Speciality;
use App\Models\Course\StudyDoc;
use App\Models\Course\StudyDuration;
use App\Models\Course\StudyForm;
use App\Models\Course\TagRefinement;
use App\Models\Course\TagSearchCourse;
use App\Models\Course\Teacher;
use App\Models\Course\TeacherState;
use App\Models\Course\UseTechnology;
use App\Models\Course\VideoType;
use App\Models\Course\WhoSuited;
use App\Models\CourseRequiredDocument;
use App\Models\Doc;
use App\Models\EduOrganization;
use App\Models\Filter\FilterCategoryTag;
use App\Models\HigherEdu\HigherEduSpeciality;
use App\Models\SpecializedSecondaryEdu\SpecializedSecondarySpeciality;


use App\Traits\Path;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class CourseService
{
    use Path;

    const TYPE_ACTION_INFO = 'info';
    const TYPE_ACTION_CATALOG = 'catalog';

    public $faqService;
    public $mainService;
    public $categoryCourseService;
    public $catalogService;

    public function __construct()
    {
        $this->faqService = new FaqService;
        $this->mainService = new MainService;
        $this->categoryCourseService = new CategoryCourseService;
        $this->catalogService = new CatalogService;
    }

    public function info()
    {   
        $higherData = HigherEduSpeciality::all();
        $specializedData = SpecializedSecondarySpeciality::all();

        $spec = [

            'higher_specialities' => $higherData,
            'secondary_specialities' => $specializedData
        ];

        return[
            'course_categories' => request()->route()->getActionMethod() === 'getBySlug' 
                    ? CategoryCourseShortResource::collection(CategoryCourse::withFilterTag()->get())
                    : CategoryCourseResource::collection(CategoryCourse::whereDoesntHave('parent')->withChildren()->get(['id','title','slug'])),
            'study_forms' => StudyForm::all(['id','title','slug']),
            'docs' => DocResource::collection(Doc::all()),
            'docs_edu' => collect($spec), 

            'course_process_types' => CourseProcessType::all(['id', 'name']),
            'course_states' => CourseState::get(['id','name']),
            'edu_organizations' => EduOrganization::get(['id','name']),
            'banner_types' => BannerType::all(),
            'banner_content_types' => BannerContentType::all(),
            'banner_colors' => [],
            'icons_who_suited' => $this->getIcons(config(Course::PATH_ICONS_WHOS)),
            'icons_utp' => $this->getIcons(config(Course::PATH_ICONS_UTP)),
            'icons_packets' => $this->getIcons(config(Course::PATH_ICONS_PACKETS)),
            'tags_refinement' => TagRefinement::all(),

            'search_tags' => TagSearchCourse::all(),
            'specialities' => Speciality::all(),
            'directions' => Direction::all(),
            'level_education' => LevelEducation::all(),
            'use_technologies' => UseTechnology::all(),
            'prices' => Price::all(),
            'flow_types' => FlowType::all(),
            'teachers' => Teacher::all(),
            'teacher_states' => TeacherState::all(),
            'video_types' => VideoType::all(),
            'lesson_types' => LessonType::all(),
        ];
    }

    /**
     * Добвление потоков к курсу
     */
    public function addFlow(CourseStoreRequest $request, Course $course)
    {
        if(!empty($flows = collect($request->flows))){
            $addresses = collect();
            $isNewDate = false;
            $studyForms = collect();

            foreach($flows as &$flow){
                $flow = collect($flow);
                $newFlowData = $flow->except(['sections', 'packets'])->toArray();
                !isset($newFlowData['study_form_id']) ?: $studyForms->push($newFlowData['study_form_id']);
                if($newFlow = $course->flows()->updateOrCreate(['id' =>  $newFlowData['id'] ?? 0], $newFlowData)){
                    $this->addProgrammSections($flow['sections'] ?? [], $newFlow, $course);
                    $this->addPackets($flow['packets'] ?? [], $newFlow, $course->id);
                    
                    if(isset($flow['start'])){
                        $isNewDate = true;
                    }
                }
            };

            $this->addStudyForms($request, $studyForms->unique()->values()->toArray());
            if($isNewDate){
                $this->updateMinMaxDates($course);
            }

            return true;
        }
        return false;
    }

    public function updateMinMaxDates(Course $course)
    {
        $now = Carbon::now();

        $course->datesStudyStart()->detach();
        $dates = $course->flows()->get(['start', 'end']);
            $data = $dates->map(function($date)use($course, $now){
                $data = $date->toArray();
                if(Carbon::make($data['start']) <= $now){
                    return [];
                }
                return [
                    'date_study_id' => DateStudy::updateOrCreate(
                        $data,
                        $data
                    )->id,
                    'course_id' => $course->id
                ];
            })
        ;

        if($data){
            $course->datesStudyStart()->attach($data?->toArray());
        }
        $date_min = $dates->min('start');
        $date_max = $dates->max('start');
        $course->update([
            'date_min' => $date_min,
            'date_max' => $date_max
        ]);

        $this->catalogService->createBitMap(
            $dates->unique()->pluck('start')->toArray(),
            $course->id,
            $this->catalogService::REDIS_FILTER_DATE_KEY
        );

        DateStudy::whereDoesntHave('courses')->delete();
    }

    public function detectMinMaxPrices(Collection $flows)
    {
        $now = Carbon::now();
        $prices = collect();
        foreach($flows as $flow){
            if($flow['type_id'] == 1 && Carbon::make($flow['start']) <= $now){
                Log::warning('Поток ID' . $flow['id'] . ' не учитывается в ценах');
                continue;
            }
            foreach($flow['packets'] as $packet){
                if((int)$packet['is_limit_sales_by_date'] && Carbon::make($packet['date_sale_end']) < $now){
                    Log::warning('Пакет ID' . $packet['id'] . ' не учитывается в ценах');
                    continue;
                }
                $prices->push((int)$packet['default_price']);
            }
        }
        return $prices;
    }

    public function updateDatePublish(CourseStoreRequest $request, Course &$course)
    {
        if($request->state_id){
            if((int)$request->state_id === CourseState::where('name', Course::STATE_ACTIVE)->first(['id'])->id){
                $isPublish = 1;
                $datePublished = Carbon::now()->format('Y-m-d H:i:s');
            }else{
                $isPublish = 0;
                $datePublished = null;
            }
            $course->update([
                'is_published' => $isPublish,
                'date_published' => $datePublished
            ]);
        }
    }

    private function addAddresses(array $addAddresses, Course $course)
    {
        if(count($addAddresses)){
            $this->catalogService->createBitMap(
                Address::all()->pluck('slug')->toArray(), 
                $course->id,
                Address::FILTER,
                0  
            );

            $addressesList = collect();
            foreach($addAddresses as $addAddress){
                $addressesList->push(Address::updateOrCreate(['title' => $addAddress], [
                    'title' => $addAddress,
                    'slug' => \Str::slug($addAddress)
                ]));
            }
            
            $course->addresses()->detach();
            $course->addresses()->attach($addressesList->pluck('id'));
            $this->catalogService->createBitMap($addressesList->pluck('slug')->toArray(), $course->id, Address::FILTER);
            $this->catalogService->clearCache();
        }
    }

    private function takeAddresses(array $lessons)
    {
        $addresses = collect();
        if(isset($lessons) && !empty($lessons)){
            foreach($lessons as $lesson){
                !isset($lesson['address']) ?: $addresses->push($lesson['address']);
            }
        }
        return $addresses->toArray();
    }

    public function addProgrammSections(Array $sections, Flow $flow, Course $course)
    {
        if(isset($sections) && !empty($sections)){
            $studyFormSlugs = StudyForm::all();
            // Обнуляем Redis для конкретного курса для конкретного св-ва
            $this->catalogService->createBitMap(
                $studyFormSlugs->pluck('slug')->toArray(), 
                $course->id,
                StudyForm::FILTER,
                0  
            );

            // Перебираем Модули
            foreach($sections as $section){
                $section = collect($section);
                $newSectionData = $section->except(['teachers', 'themes'])->toArray();
                if($newSection = $flow->sections()->updateOrCreate(['id' =>  $newSectionData['id'] ?? 0], $newSectionData)){
                    $addresses = collect();

                    $this->addLessonsToSection($section['lessons'] ?? [], $newSection, $studyFormSlugs, $course->id);
                    if(isset($section['lessons'])){
                        $addresses->push($this->takeAddresses($section['lessons']));
                    }
                    $this->addAddresses($addresses->collapse()->unique()->toArray(), $course);
                }

            }
        }
    }

    public function addLessonsToSection(Array $lessons, CourseSection $newSection, $studyFormSlugs, $courseId)
    {
        if(isset($lessons) && !empty($lessons)){
            foreach($lessons as $lesson){
                if($newLesson = $newSection->lessons()->updateOrCreate(['id' =>  $lesson['id'] ?? 0], collect($lesson)->except(['teachers','themes'])->toArray())){
                    $this->addTeachersToLesson($lesson['teachers'] ?? [], $newLesson);
                    $this->addThemesToLesson($lesson['themes'] ?? [], $newLesson);
                }
            }

            $this->catalogService->createBitMap(
                $studyFormSlugs->whereIn('id', $newSection->lessons()->pluck('study_form_id')->toArray())->pluck('slug')->toArray(), 
                $courseId, 
                StudyForm::FILTER
            );
            $this->catalogService->clearCache();
        }
    }

    public function deleteLessonFromSection()
    {

    }

    public function addThemesToLesson(Array $themes, CourseSectionLesson $newLesson)
    {
        if(isset($themes) && !empty($themes)){
            foreach($themes as $them){
                $newLesson->themes()->updateOrCreate(['id' =>  $them['id'] ?? 0], $them);
            }
        }
    }

    public function addPackets(Array $packets, Flow $flow, int $courseId)
    {
        if(isset($packets) && !empty($packets)){
            foreach($packets as $packet){
                $packet = collect($packet);
                $newPacketData = $packet->except(['descriptions', 'sale_rules'])->toArray();
                if($newPacket = $flow->packets()->updateOrCreate(['id' =>  $newPacketData['id'] ?? 0], $newPacketData)){
                    $key_price = $this->explodePricesForRedis($newPacketData['default_price'] ?? null);
                    if($key_price){
                        $this->catalogService->createBitMap(
                            [$key_price], 
                            $courseId, 
                            $this->catalogService::REDIS_FILTER_PRICE_KEY
                        );
                        $this->catalogService->clearCache();
                    }
                    $this->addDescriptionsToFlow($packet['descriptions'] ?? [], $newPacket);
                    $this->addSaleRulesToFlow($packet['sale_rules'] ?? [], $newPacket);
                }
            }
        }
    } 

    public function explodePricesForRedis($price)
    {
        $price = (int)$price;
        if($price === null){
            return $price;
        }
        $min_whole_part = $price - ($price % Course::PRICE_DELIMETER);
        if($min_whole_part === $price){
            return (string)$price;
        }
        $max_whole_part = $min_whole_part + Course::PRICE_DELIMETER;
        return (string) $min_whole_part . '-' . $max_whole_part;
    }

    public function addDescriptionsToFlow(Array $descriptions, Packet $newPacket)
    {
        if(isset($descriptions) && !empty($descriptions)){
            foreach($descriptions as $description){
                $newPacket->descriptions()->updateOrCreate(['id' =>  $description['id'] ?? 0], $description);
            }
        }
    }

    public function addSaleRulesToFlow(Array $sale_rules, Packet $newPacket)
    {
        if(isset($sale_rules) && !empty($sale_rules)){
            foreach($sale_rules as $sale_rule){
                $newPacket->saleRules()->updateOrCreate(['id' =>  $sale_rule['id'] ?? 0], $sale_rule);
            }
        }
    }

    /**
     * Попытка привязать учителей к разделам потока + статус учителя в контексте этого потока НАДО ДОРАБОТАТЬ!!!!
     */
    public function addTeachersToLesson(Array $teachers, CourseSectionLesson $newLesson)
    {
        $newLesson->teachers()->detach();
        if(isset($teachers) && !empty($teachers)){
            $newLesson->teachers()->attach($teachers);
        }
    }

    public function addShoppingOffers(CourseStoreRequest $request)
    {
        if($shopping_offers = $request->shopping_offers){
            foreach($shopping_offers as &$item){
                $item['course_id'] = $request->course_id;
                ShoppingOffer::updateOrCreate(['id' =>  $item['id'] ?? 0], $item);
            };
        }
    }

    public function addWhoSuited(CourseStoreRequest $request)
    {
        if($who_suited = $request->who_suited){
            foreach($who_suited as &$item){
                $item['course_id'] = $request->course_id;
                WhoSuited::updateOrCreate(['id' =>  $item['id'] ?? 0], $item);
            };
        }
    }

    public function addWhoRefinementTags(CourseStoreRequest $request)
    {
        $refinement_tag_ids = $request->refinement_tag_ids;
        if(isset($refinement_tag_ids)){
            if($course = Course::find($request->course_id)){
                
                $this->catalogService->createBitMap(
                    TagRefinement::all()->pluck('slug')->toArray(), 
                    $course->id,
                    TagRefinement::FILTER,
                    0  
                );

                if (count($refinement_tag_ids)) {
                    $redisFlag = 1;  
                    $course->tagRefinements()->detach();
                    $course->tagRefinements()->attach($refinement_tag_ids);
                } else {
                    $redisFlag = 0;  
                    $course->tagRefinements()->detach();
                }
                $this->catalogService->createBitMap(
                    TagRefinement::whereIn('id', $refinement_tag_ids)->pluck('slug')->toArray(), 
                    $course->id,
                    TagRefinement::FILTER,
                    $redisFlag  
                );

                $this->catalogService->clearCache();
            }
        }
    }

    public function addCategories(Course $course, array $category_ids = null)
    {
        if($course){
            $propertyForReset = $this->resetCategoryBitMapForCourse($course->id);
            if($category_ids && count($category_ids)){
                $course->categoryCourse()->detach();
                $this->resetRedisValuesByProperty($course, $category_ids);
                $categoryIds = $this->categoriesPrepareParams($course, $category_ids);
                $course->categoryCourse()->attach($categoryIds);
            }elseif($category_ids !== null && count($category_ids) == 0){
                $course->categoryCourse()->detach();
                $this->resetRedisValuesByProperty($course, $category_ids);
                $category_ids = null;
            }else{
                $category_ids = $course->tree;
            }
            $course->tree = $category_ids;
            $this->catalogService->clearCache();
        }
    }

    public function resetRedisValuesByProperty(Course $course, array $category_ids)
    {
        if($course->tree){
            if($deletedKeysRedis = $this->compareMultiArrays($course->tree->toArray(), $category_ids)){
                $deletedKeysRedis->each(function($deleteArr)use($course){
                    $allSlugKeys = $this->categoryCourseService->getAllParentCategorySlug($deleteArr);
                    $this->catalogService->createBitMap(
                        $allSlugKeys, 
                        $course->id,
                        "",
                        0
                    );
                });
            }
        }
    }

    public function compareMultiArrays(array $firstArray, array $secondArray)
    {
        $diff = collect();
        foreach($firstArray as $compare){
            $comp = 0;
            foreach($secondArray as $comparable){
                $compare !== $comparable ?: $comp = 1;
            }
            $comp !== 0 ?: $diff->push($compare);
        }
        return $diff->count() ? $diff : false;
    }

    public function resetCategoryBitMapForCourse(int $courseId)
    {
        $filters = FilterCategoryTag::withCategories()->get()->toArray();
        $result = collect();
        foreach($filters as $filter){
            $result->push(
                collect($filter['categories'])->pluck('slug')->map(fn($slug) =>  $filter['slug'] . '_' . $slug)->toArray()
            );
        }
        return $result->collapse()->toArray();
    }

    /**
     * Подготавливает параметры для создания связей курса с категориями и записи в Redis
     */
    public function writeBitMapToRedis(Course $course)
    {
        if($course){

            $categoryIds = $course->categoryCourse->pluck('id')->toArray();
            $addressesIds = $course->addresses->pluck('id')->toArray();
            $durationIds = $course->duration->pluck('id')->toArray();
            $studyFormsIds = $course->studyForms->pluck('id')->toArray();
            $tagRefinementsIds = $course->tagRefinements->pluck('id')->toArray();
        
            $result = collect();
            $categoryKeys = $this->categoryCourseService->getAllParentCategorySlug($categoryIds);
            $addressesKeys = $this->categoryCourseService->getStaticPropertyRedisKeys(Address::class, $addressesIds);
            $durationKeys = $this->categoryCourseService->getStaticPropertyRedisKeys(StudyDuration::class, $durationIds);
            $studyFormsKeys = $this->categoryCourseService->getStaticPropertyRedisKeys(StudyForm::class, $studyFormsIds);
            $tagRefinementsKeys = $this->categoryCourseService->getStaticPropertyRedisKeys(TagRefinement::class, $tagRefinementsIds);
            $pricesKeys = $this->getPricesRedisKeys($course);

            $result = $result->merge($categoryKeys)
                ->merge($categoryKeys)
                ->merge($addressesKeys)
                ->merge($durationKeys)
                ->merge($studyFormsKeys)
                ->merge($tagRefinementsKeys)
                ->merge($pricesKeys)
                ->unique()->values()->toArray()
            ;

            $this->catalogService->createBitMap(
                $result, 
                $course->id
            );

            $this->updateMinMaxDates($course);

            $this->catalogService->clearCache();

            return $result;
        }
        return false;
    }

    public function getPricesRedisKeys(Course $course)
    {
        $pricePrefix = $this->catalogService::REDIS_FILTER_PRICE_KEY;
        return $course->flows->map(function($flow){
            $flow->prices = $flow->packets->pluck('default_price');
            return  $flow;
        })->pluck('prices')->collapse()->unique()
            ->map(fn($price) => $price = $pricePrefix . '_' . $this->explodePricesForRedis((string)$price));
    }

    public function categoriesPrepareParams(Course $course, array $categoryIds = null)
    {
        if($categoryIds && $course){
            $result = collect();
            foreach($categoryIds as $categoryTree){
                $result->push($categoryTree);
                $allSlugKeys = $this->categoryCourseService->getAllParentCategorySlug($categoryTree);
                $this->catalogService->createBitMap(
                    $allSlugKeys, 
                    $course->id
                );
            }

            return $result->collapse()->unique()->values()->toArray();
        }
        return false;
    }

    

    public function addTagSearch(CourseStoreRequest $request)
    {
        if($search_tags = $request->search_tag_ids){
            if($course = Course::find($request->course_id)){
                $course->searchTags()->detach();
                $course->searchTags()->attach($search_tags);
            }
        }
    }

    public function addEduDocs(CourseStoreRequest $request)
    {
        $required_docs = $request->doc_edu_ids;
        if($required_docs){
            foreach($required_docs as $doc){
                $diplom = null;
                $diplom = CourseRequiredEduDocument::updateOrCreate([
                    'id'        => $doc['id'] ?? 0,
                    'course_id' => $request->course_id,
                    ],
                    [
                        'document_id'   => $doc['document_id'] ?? null,
                        'course_id'     => $request->course_id,
                        'description'   => $doc['description'],
                        'type_edu'      => $doc['type_edu']
                    ]
                );

                $diplom->replaceHigherSpecialities()->sync($doc['replace_higher_specialities']);
                $diplom->replaceSpecializedSpecialities()->sync($doc['replace_specialized_specialities']);

            }

            
        }elseif(isset($required_docs) && count($required_docs) === 0){
            $course = Course::find($request->course_id);
            $course->docEduDirection()->delete();
        }
    }

    public function addDocs(CourseStoreRequest $request)
    {
        $required_docs = $request->doc_ids;
        if($required_docs){
            foreach($required_docs as $doc){
                $replacement_documents = collect();
                $courseRequiredDocument = null;
                $courseRequiredDocument = CourseRequiredDocument::updateOrCreate([
                    'id' => $doc['id'] ?? 0,
                    'course_id' => $request->course_id,
                    ],
                    [
                        'document_id' => $doc['document_id'] ?? null,
                        'course_id' => $request->course_id,
                        'description' => $doc['description'],
                    ]
                );

                $replacement_documents->push(
                        collect($doc['replacement_documents'])->map(fn ($item) => [
                        'course_required_document_id' => $courseRequiredDocument->id,
                        'document_id' => $item
                    ])->toArray()
                );
                $courseRequiredDocument->replacementDocuments()->detach();
                $courseRequiredDocument->replacementDocuments()->attach($replacement_documents->collapse()->toArray());
            }
        }elseif(isset($required_docs) && count($required_docs) === 0){
            $course = Course::find($request->course_id);
            $course->docs()->delete();
        }
    }

    public function deleteRequiredDocument(DeleteRequiredDocumentRequest $request)
    {
        try{
            $courseRequiredDocument = CourseRequiredDocument::find($request->id);
            $courseRequiredDocument->replacementDocuments()->detach();
            $courseRequiredDocument->delete();

            return Course::find($courseRequiredDocument->course_id);
        }catch(Exception $e){
            return false;
        }
    }

    public function deleteRequiredEduDocument(DeleteRequiredEduDocumentRequest $request)
    {
        try{
            $courseRequiredDocument = CourseRequiredEduDocument::find($request->id);
    
            $courseRequiredDocument->replacementDocuments()->detach();
            $courseRequiredDocument->delete();

            return Course::find($courseRequiredDocument->course_id);
        }catch(Exception $e){
            return false;
        }
    }

    /**
     * Разобраться как его организовать
     */
    public function addDuration(CourseStoreRequest $request)
    {
        if($request->academic_days && $request->academic_hours){
            $course = Course::find($request->course_id);

            $this->catalogService->createBitMap(
                StudyDuration::all()->pluck('slug')->toArray(), 
                $course->id,
                StudyDuration::FILTER,
                0  
            );

            $duration = $request->academic_days . ' дней (' . $request->academic_hours . ' ч.)';
            $this->catalogService->createBitMap([\Str::slug($duration)], $request->course_id, StudyDuration::FILTER);
            $studyDurationId = StudyDuration::updateOrCreate([
                    'title' => $duration,
                ], [
                'title' => $duration,
                'slug' => \Str::slug($duration)
            ])->id;
            $course->duration()->detach();
            $course->duration()->attach([$studyDurationId]);
            $this->catalogService->clearCache();
        }
    }

    public function addStudyForms(CourseStoreRequest $request, array $studyFormIds)
    {
        if(count($studyFormIds) > 0){
            if($course = Course::find($request->course_id)){
                $course->studyForms()->detach();
                $course->studyForms()->attach($studyFormIds);
            }
        }
    }

    public function addSpecialities(CourseStoreRequest $request)
    {
        $specialities = $request->specialities;
        if($specialities !== null){
            if($course = Course::find($request->course_id)){
                $course->specialities()->detach();
                $course->specialities()->attach($specialities);
            }
        }
    }

    public function addDirections(CourseStoreRequest $request)
    {
        $directions = $request->directions;
        if($directions !== null){
            if($course = Course::find($request->course_id)){
                $course->directions()->detach();
                $course->directions()->attach($directions);
            }
        }
    }

    public function addUseTechnologies(CourseStoreRequest $request)
    {
        if($use_technology_ids = $request->use_technology_ids){
            if($course = Course::find($request->course_id)){
                $course->useTechnologies()->detach();
                $course->useTechnologies()->attach($use_technology_ids);
            }
        }
    }

    public function addTeachers(CourseStoreRequest $request)
    {
        $teachers = $request->teacher_ids;
        if($teachers !== null){
            if($course = Course::find($request->course_id)){
                $course->teachers()->detach();
                $course->teachers()->attach($teachers);
            }
        }
    }

    /**
     * Добавляет различные картинки к курсу
     */

    public function addCourseDocImages($fileImages, int $course_id, int $type)
    {
        $result = $this->mainService->addMedia(Course::PATH_IMG_SIMPLE, $fileImages);
        if($result){
            foreach($result as $key => &$image){
                $dopData = [
                    'course_id' => $course_id,
                    'type' => $type,
                    'name' => $image->get('name'),
                ];
                $image = $image->merge($dopData);
                $image['id'] = CourseDocImage::updateOrCreate($dopData, $image->toArray())->id;
                $result[$key] = $image->except(['name', 'course_id', 'type']);
            };
            return $result->toArray();
        }
        return false;
    }

    public function getDocTakeImages(Array $names)
    {
        foreach($names as &$name){
            $name['name'] = $this->simpleImagePath($name['name'], Course::PATH_IMG_SIMPLE);
        }
        return $names;
    }

    public function addDocTake(CourseDocTakeStoreRequest $request)
    {
        $docTake = CourseDocTake::updateOrCreate(
            [
                'id' => $request->id ?? 0, 
                'course_id' => $request->course_id, 
            ], 
            $request->except(['id','images'])
        );
        if($images = $request->file('images')){
            $images = $this->mainService->addMedia(Course::PATH_DOC_TAKE_IMG, $images, false);
            if($images){
                foreach($images as $key => &$image){
                    $dopData = [
                        'doc_take_id' => $docTake->id,
                        'name' => $image->get('name'),
                        'url' => $image->get('url'),
                    ];
                    DocTakeImage::updateOrCreate($dopData, $dopData);
                };
            }
        }
        return $docTake;
    }

    public function addStudyDocs(CourseStoreRequest $request)
    {
        if($study_docs = $request->study_docs){
            foreach($study_docs as &$study_doc){
                $study_doc['course_id'] = $request->course_id;
                StudyDoc::updateOrCreate(['id' => $study_doc['id'] ?? 0], $study_doc);
            };
        }
    }

    public function getIcons($path)
    {
        return collect(Storage::disk('root')->files($path))
            ->map(fn($icon) => $icon = url('/') . Storage::url($icon));
    }

    public function addFaq(CourseStoreRequest $request, Course $course)
    {
        if($course && isset($request->faqs)){
            foreach($request->faqs as $itemFaq){
                $faq = Faq::updateOrCreate(
                    ['id' =>  $itemFaq['id'] ?? 0],
                    [
                        'title' => $itemFaq['title'],
                        'answer' => $itemFaq['answer'],
                        'position' => $itemFaq['position'],
                        'contains_subsections' => $itemFaq['contains_subsections'],
                        'course_id' => $course->id,
                    ]
                );
                $this->addQuestions($itemFaq['questions'], $faq);
            }
            
            return $course->faqs;
        }
        return[];
    }

    /**
     * Удаление раздела FAQ + рекурсивное удаление всех связанных сущностей (вопрос/ответ)
     */
    public function deleteFaq(FaqDeleteRequest $request, Course $course)
    {
        if($faq = $course->faqs->find($request->id)){
            $faq->questions->each(fn($question) => $question->answer()->delete());
            $faq->questions()->delete();
            return $faq->delete();
        }
        return false;
    }

    /**
     * Удаление ответа к вопросу у определённого раздела FAQ
     */
    public function deleteFaqAnswer(FaqDeleteRequest $request, Course $course)
    {
        if($faq = $course->faqs->find($request->id)){
            return $faq->questions()->find($request->question_id)->answer()->delete();
        }
        return false;
    }

    /**
     * Удаление вопроса у определённого раздела FAQ
     */
    public function deleteFaqQuestion(FaqDeleteRequest $request, Course $course)
    {
        if($faq = $course->faqs->find($request->id)){
            if($this->deleteFaqAnswer($request, $course)){
                return $faq->questions()->find($request->question_id)->delete();
            }
        }
        return false;
    }

    private function addQuestions(Array $questions, Faq $faq)
    {
        foreach($questions as $questionItem){
            $question = $faq->questions()->updateOrCreate(
                ['id' =>  $questionItem['id'] ?? 0], 
                ['title' => $questionItem['title'], 'position' => $questionItem['position']]);
            $question->answer()->updateOrCreate(
                ['id' =>  $questionItem['id'] ?? 0], 
                ['text' => $questionItem['answer'], 'faq_question_id' => $question->id]
            );
        }
    }

    public function addReview(CourseReviewStoreRequest $request)
    {
        $course = Course::findOrFail($request->course_id);
        $course->reviews()->save(CourseReview::create($request->all()));
        if($request->rating){
            $this->updateRating($course);
        }
        return $course->reviews()->with('childsRecursive')->get();
    }

    public function updateRating(Course $course)
    {
        $course->update([
            'rating' => $course->ratingValue()
        ]);

    }

    private function parseUrl(string $url)
    {
        $result = collect([
            'uriParts' => collect(),
            'paramParts' => collect(),
        ]);

        $uri = $params = null;

        if(\Str::is('*?*', $url)){
            $urlParts = \Str::of($url)->explode('?');
            $uri = $urlParts->first();
            $params = $urlParts->last();
        }

        if($uri){
            $result['uriParts']->push(\Str::of($uri)->explode('/')); 
        }
        
        if($params){
            $result['paramParts']->push(\Str::of($params)->explode('&')); 
        }

        return $result;
    }

    public function addVideo(CourseVideoStoreRequest $request)
    {
        $file = $request->file('file');
        if(!empty($file)){
            $data = $this->mainService->addMedia(Course::PATH_VIDEO, $file);
            if($data){
                Course::findOrFail($request->course_id)->update([
                    'video_file' => $data->first()->get('name')
                ]);
                return $data->first()->get('url');
            }
        }
        return null;
    }

    public function deleteVideo(CourseVideoDeleteRequest $request)
    {
        $course = Course::findOrFail($request->course_id);
        if($this->mainService->deleteMedia(Course::PATH_VIDEO, $course->video_file)){
            $course->update([
                'video_file' => null
            ]);
            return true;
        }
        return false;
    }

    public function deleteDocTakeImage(int $id)
    {
        $docTakeImage = DocTakeImage::findOrfail($id);
        if($this->mainService->deleteMedia(Course::PATH_DOC_TAKE_IMG, $docTakeImage->name)){
            $docTakeImage->delete();
            return true;
        }
        return false;
    }

    public function deleteDocTake(int $id)
    {
        $docTake = CourseDocTake::findOrfail($id);
        foreach($docTake->images as $image){
            $this->mainService->deleteMedia(Course::PATH_DOC_TAKE_IMG, $image->name);
            $image->delete();
        }
        $docTake->delete();
        return true;
    }

    /**
     * Добавление основных документов для поступления на курс во время его создания,
     *  для которых могут быть заменяющие документы
     */
    public function addRequiredDocs(CourseStoreRequest $request)
    {
        return; // DONT USE !!!
        if($required_docs = $request->required_docs){
            foreach($required_docs as $doc){
                $replacement_documents = collect();
                $courseRequiredDocument = null;
                $courseRequiredDocument = CourseRequiredDocument::updateOrCreate([
                    'id' => $doc['id'] ?? 0,
                    'course_id' => $request->course_id,
                    ],
                    [
                        'document_id' => $doc['document_id'] ?? null,
                        'course_id' => $request->course_id,
                        'description' => $doc['description'],
                    ]
                );

                $replacement_documents->push(
                        collect($doc['replacement_documents'])->map(fn ($item) => [
                        'course_required_document_id' => $courseRequiredDocument->id,
                        'document_id' => $item
                    ])->toArray()
                );
                $courseRequiredDocument->replacementDocuments()->detach();
                $courseRequiredDocument->replacementDocuments()->attach($replacement_documents->collapse()->toArray());
            }
        }
    }

    public function addPreview(AddPreviewRequest $request)
    {
        $file = $request->file('preview');
        if(!empty($file)){
            $data = $this->mainService->addMedia(Course::PATH_PREVIEW, $file);
            if($data){
                Course::findOrFail($request->course_id)->update([
                    'preview' => $data->first()->get('name')
                ]);
                return $data->first()->get('url');
            }
        }
        return null;
    }

    public function deletePreview(PreviewDeleteRequest $request)
    {
        $course = Course::findOrFail($request->course_id);
        if($this->mainService->deleteMedia(Course::PATH_PREVIEW, $course->preview)){
            $course->update([
                'preview' => null
            ]);
            return true;
        }
        return false;
    }

    public function like(Course $course)
    {
        if($like = $course->likes()->where('user_id', auth()->user()->id)->first()){
            $like->delete();
            return false;
        }
        $course->likes()->create([
            'user_id' => auth()->user()->id
        ]);
        return true;
    }
}