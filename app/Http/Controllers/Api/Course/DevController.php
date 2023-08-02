<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\Banner;
use App\Models\Course\BidCourse;
use App\Models\Course\CategoryCourse;
use App\Models\User;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;

class DevController extends Controller
{
    private $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function checkToken()
    {
        User::first('id','>',0)->update([
            'verified' => 1
        ]);
        return response()->json(['data' => User::with('withTokens', 'pin')->get()->toArray()], 200);
    }

    public function changePhone(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update([
            'phone' => $request->new_phone
        ]);
        $user->individualProfile()->update([
            'phone' => $request->new_phone
        ]);
        
        return response()->json($user->with('individualProfile')->get(), 200);
    }

    public function clearRedis()
    {
        $this->catalogService->clearRedis();
        return response()->json(['message' => 'ok'], 200);
    }

    public function getAllRedisKeys(Request $request)
    {
        if((int)$request->clear === 1){
            $this->catalogService->clearCache();
            $this->catalogService->clearRedis();
        }

        return response()->json($this->catalogService->getAllRedisKeys(), 200);
    }

    public function clearCache()
    {
        $this->catalogService->clearCache();
        return response()->json(['message' => 'ok'], 200);
    }

    public function redisCreateCatalog()
    {
        Artisan::call('redis:createBitMapCatalog');
        return response()->json(['message' => 'ok'], 200);
    }

    public function updateMinMaxPrices()
    {
        Artisan::call('course:updateMinMaxPrices');    
        return response()->json(['message' => 'ok'], 200);
    }

    public function clearNullableStatusCourses()
    {
        Artisan::call('course:clearNullableStatusCourses');
        return response()->json(['message' => 'ok'], 200);
    }

    public function resetCategoriesParents(Request $request)
    {
        $params = [
            'parent_id' => null
        ];
        if($request->withTags){
            $params = array_merge($params, ['tag_id' => null]);
        }
        return response()->json(['message' => 'ok'], 200);
    }

    public function clearAllCourses()
    {
        Banner::truncate();
        CategoryCourse::truncate();
        \DB::table('category_course_speciality')->truncate();
        \DB::table('category_course_speciality_faq_answers')->truncate();
        \DB::table('category_course_speciality_faq_questions')->truncate();
        \DB::table('category_course_speciality_faqs')->truncate();
        \DB::table('course_addresses')->truncate();
        \DB::table('course_addresses_relation')->truncate();
        \DB::table('course_comments')->truncate();
        \DB::table('course_dates_study')->truncate();
        \DB::table('course_direction')->truncate();
        \DB::table('course_doc_images')->truncate();
        \DB::table('course_doc_take_images')->truncate();
        \DB::table('course_docs')->truncate();
        \DB::table('course_docs_take')->truncate();
        \DB::table('course_duration')->truncate();
        \DB::table('course_edu_docs_replacement')->truncate();
        \DB::table('course_edu_organizations')->truncate();
        \DB::table('course_level_education')->truncate();
        \DB::table('course_processes')->truncate();
        \DB::table('course_ratings')->truncate();
        \DB::table('course_refinements')->truncate();
        \DB::table('course_relation_category')->truncate();
        \DB::table('course_required_documents')->truncate();
        \DB::table('course_required_edu_documents')->truncate();
        \DB::table('course_reviews')->truncate();
        \DB::table('course_section_teachers')->truncate();
        \DB::table('course_section_themes')->truncate();
        \DB::table('course_sections')->truncate();
        \DB::table('course_section_lessons')->truncate();
        \DB::table('course_speciality')->truncate();
        \DB::table('course_study_docs')->truncate();
        \DB::table('course_study_form')->truncate();
        \DB::table('course_tag_refinements')->truncate();
        \DB::table('course_tag_search')->truncate();
        \DB::table('course_teacher')->truncate();
        \DB::table('course_use_technology')->truncate();
        \DB::table('courses')->truncate();
        \DB::table('dates_study')->truncate();
        \DB::table('document_course_required_documents')->truncate();
        \DB::table('faq_answers')->truncate();
        \DB::table('faq_questions')->truncate();
        \DB::table('faqs')->truncate();
        \DB::table('flows')->truncate();
        \DB::table('packet_descriptions')->truncate();
        \DB::table('packet_sale_rules')->truncate();
        \DB::table('packets')->truncate();
        \DB::table('section_topics')->truncate();
        \DB::table('study_duration')->truncate();
        \DB::table('teacher_course_section')->truncate();
        \DB::table('text_block_shopping_offer')->truncate();
        \DB::table('text_block_who_suited')->truncate();
        \DB::table('topic_programm')->truncate();

        $this->clearCache();
        $this->clearRedis();

        return response()->json(['message' => 'ok'], 200);
    }
}
