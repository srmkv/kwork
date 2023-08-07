<?php
namespace App\Http\Controllers\Api\Course\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Course\NeededSpeciality;
use App\Models\Course\NeedOtherType;
use App\Models\Course\Course;
use App\Models\User;
use App\Models\Order;
use App\Models\Order\BusinessOrder;

use App\Services\Order\OrderCommonService;
use App\Traits\Company;
use App\Http\Resources\Order\FilterOrderResource;
use App\Http\Resources\Order\BusinessOrderResource;
use App\Http\Resources\Course\BusinessOrderCourseResource;


class BusinessOrderController extends Controller
{   
    public function __construct(OrderCommonService $orderService) 
    {
        $this->orderService = $orderService;
    }

    public function newBusinessOrder(Request $request)
    {   
        $courses = $request->courses;
        $user = User::find(Auth::id());
        $businessOrder = new BusinessOrder;
        $businessOrder->order_body = $request->only(['courses']);
        $businessOrder->author = $request->only(['author']);

        $businessOrder->user_id = $user->id;
        $businessOrder->pay_method_id = $request->pay_method_id;  
        $businessOrder->status_id = $request->status_id;  
        $businessOrder->price = $this->orderService->formationPriceBusiness($request->only(['courses']));
        $businessOrder->save();


        if($businessOrder->status_id == $this->orderService::STATUS_SUBMITTED) {
            $this->orderService->formationPersonalOrders($businessOrder);
        } 

        return BusinessOrderResource::make($businessOrder);
    }


    public function test(Request $request)
    {   
        $businessOrder = BusinessOrder::find(32);
        return $this->orderService->formationPersonalOrders($businessOrder);
    }

    public function editBusinessOrder(Request $request, $order_id)
    {   
        $businessOrder = BusinessOrder::find($order_id);
        if(isset($request->status_id) && $request->status_id == $this->orderService::STATUS_CANCELLED) {

            return $this->orderService->orderBusinessCancelled($businessOrder);
        } else {
            $courses = $request->courses;
            $user = User::find(Auth::id());
            $businessOrder->order_body = $request->only(['courses']);
            $businessOrder->price = $this->orderService->formationPriceBusiness($request->only(['courses']));
            $businessOrder->save();
            if($businessOrder->status_id == $this->orderService::STATUS_SUBMITTED) {
                $this->orderService->formationPersonalOrders($businessOrder);
            } 
            return BusinessOrderResource::make($businessOrder);
        }
    }


    public function filterBusinessOrder(Request $request)
    {
        $courses = $request->courses;
        return FilterOrderResource::collection(Course::find($courses));
    }

    public function getBusinessOrder(Request $request, $order_id)
    {
        $businsessOrder = BusinessOrder::find($order_id);
        return BusinessOrderResource::make($businsessOrder);        
    }

    // список всех курсов внутри бизнес заявки
    public function getCoursesForOrder(Request $request, $order_id)
    {   
        return $this->orderService->listCourses($order_id);
    }

    public function getPriceBusinessOrder(Request $request)
    {
        $packets = $request->packets;
        return $this->orderService->priceBusiness($request->packets);
    }

    public function listOrders(Request $request)
    {
        $company_id = $request->company_id;
        $type = $request->type;
        return $this->orderService->listBusinessOrders($type, $company_id);

    }

    public function multiOrders(Request $request)
    {
        $user = User::find(Auth::id());
        return $this->orderService->multiPay($request->order_ids, $request->packet_id, $request->flow_id);

    }


    // TAB CORUSES
    // список студентов
    public function getListenersIntoCourse(Request $request)
    {
        $flow_id = $request->flow_id;
        $businsessOrder = BusinessOrder::find($request->business_order_id);
        return $this->orderService->listenersIntoCourse($flow_id, $businsessOrder);
    }

    // добавить в поток студента
    public function createStudentsCourse(Request $request)
    {
        $flow_id = $request->flow_id;
        $students = $request->students;
        $businessOrder = BusinessOrder::find($request->business_order_id);
        return $this->orderService->createStudents($flow_id, $students, $businessOrder);
    }

    // удалить студента с потока
    public function deleteStudentCourse(Request $request)
    {   
        $businessOrder = BusinessOrder::find($request->business_order_id);

        return $this->orderService->deleteStudentCourse(
            $request->profile_id,
            $request->flow_id,
            $businessOrder);
    }

    // без студентов
    public function createCourse(Request $request)
    {
        $businessOrder = BusinessOrder::find($request->order_id);
        $course_id = $request->course_id;
        $flow_id = $request->flow_id;
        $packet_id = $request->packet_id;
        return $this->orderService->createCourseBusinessOrder($businessOrder, $course_id, $flow_id, $packet_id);
    }
    // удалить полностью курс
    public function deleteCourse(Request $request)
    {
        $flow_id = $request->flow_id;
        $businessOrder = BusinessOrder::find($request->business_order_id);
        return $this->orderService->deleteCourse($flow_id, $businessOrder);
    }


    // TAB STUDENTS

    // все студенты в заявке
    public function listenersInformation(Request $request, $order_id)
    {
        $user = User::find(Auth::id());
        $businessOrder = BusinessOrder::find($order_id);

        $searchUserWord = $request["query"] ?? '';

        return $this->orderService->listenersForBusinessOrder($businessOrder,$searchUserWord);
    }

    public function deleteStudentEntireBusiness(Request $request)
    {
        $student_id = $request->student_id;
        $businessOrder = BusinessOrder::find($request->business_order_id);
        return $this->orderService->deleteStudentEntire($student_id, $businessOrder);
    }


    // FILTER COURSES

    public function resultFilters(Request $request)
    {
        $directions = $request->directions;
        $specialities = $request->specialities;
        $study_forms = $request->study_forms;
        $study_types = $request->study_types;
        $count_students = $request->count_students;
        $business_order_id = $request->business_order_id;
        $dates = $request->dates;
        // тип обучения ( переквалииф)
        $type_study = $request->type_study;
        $params = collect([
            'business_order_id' => $business_order_id,
            'directions' => $directions,
            'specialities' => $specialities,
            'study_forms' => $study_forms,
            'study_types' => $study_types,
            'count_students' => $count_students,
            'dates' => $dates
        ]);
        return $this->orderService->businessCoursesFilters($params);
    }

    public function infoFilters(Request $request, $business_order_id)
    {
        return $this->orderService->getDataCoursesFilters($business_order_id);
    }

}
