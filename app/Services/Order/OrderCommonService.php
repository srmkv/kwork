<?php
namespace App\Services\Order;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\HigherEdu;
use App\Models\OtherDoc;
use App\Models\SpecializedSecondaryEdu;

use App\Models\Order;
use App\Models\Course\Course;
use App\Models\Course\CategoryCourse;

use App\Models\Course\Flow;
use App\Models\Course\Packet;
use App\Models\Order\AdmissionDocument;
use App\Models\Order\FlowDocument;
use App\Models\Order\BusinessOrder;
use App\Models\Profiles\ProfileIndividual;

use App\Models\Chat\Message;
use App\Models\Chat\ChatRoom;

use App\Models\HigherEdu\HigherEduLevel;
use App\Services\Diplom\DiplomService;

use App\Services\Order\OrderSubmissionDocumentsService;
use App\Services\Order\OrderValidateActionService;

use App\Services\User\EmployeeService;


use App\Http\Resources\Order\ListBusinessOrderResource;
use App\Http\Resources\Order\BusinessOrderResource;
use App\Http\Resources\Order\ListFlowsStudentsResource;

use App\Http\Resources\Course\BusinessOrderCourseResource;
use App\Http\Resources\Course\BusinessStudentsCourse;
use App\Http\Resources\Order\AdmissionDocumentResource;

use App\Services\PacketService;
use App\Services\FlowService;
use App\Services\Chat\BusinessChatService;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Traits\Profile;
use App\Traits\CourseTrait;

class OrderCommonService
{   

    // TODO #99 
    // РАЗДЕЛИТЬ ЛОГИКУ СТАТУСОВ ОРДЕРА И СТАТУС ПЛАТЕЖА(!)

    const STATUS_CONSULTATION = 1; // только что создали , или редактируем
    const STATUS_SUBMITTED = 2; // черновик пропадает ( интерфейс слева ), формируются все вкладки с доками/чатами студентов
    const STATUS_PAID = 3;  // оплатили, меняется после успешного хука ( полная оплата)
    const STATUS_CANCELLED = 6; // нажали отменить заявку // закрыто
    const STATUS_DRAFT = 8; // сохранили в черновик

    const STATUS_PART_PAID = 10; // частично оплачен, или заявка в рассрочке

    // TYPE STUDY

    const RETRAINING = 'perepodgotovka';
    const REQUALIFICATION = 'perekvalifikaciia';


    public function __construct(
        OrderSubmissionDocumentsService $orderSubmission,
        BusinessChatService $businessChat,
        OrderValidateActionService $orderValidateAction,
    )
    {
        $this->orderSubmission = $orderSubmission;
        $this->businessChat = $businessChat;
        $this->orderValidateAction = $orderValidateAction;
    }


    public function calculatePriceIndividual($course)
    {
        $price = 0;
        foreach ($course->flows  as $flow) {
            $price += $flow->packets->sum('default_price');
        }
        return $price;
    }
    
    public function flowDocuments($course, $order_id)
    {
        foreach ($course->docsTake  as $doc) {
            
            $flow = new FlowDocument;
            $flow->course_docs_take_id = $doc->id;
            $flow->order_id = $order_id;
            $flow->save();
        }
    }


    public function formationPersonal($types, $user, $course_id)
    {   
        $collectPersonal = collect();
        foreach ($types as $type) {
            switch ($type) {
                case 1:
                    $collectPersonal['passports']  = $user->passports->map(function ($passport) {
                        $q = [
                            "fullname" => $passport->fullname,
                            "id" => $passport->id,
                        ];
                        return $q;
                    });
                    break;
                case 2:
                    $collectPersonal['snils']  = $user->snils;
                    break;


                case 3:
                    $collectPersonal['schools']  = $user->secondaryEdu->map(function($school) {
                            $r = [
                                "id" => $school->id,
                                "title" => $school->title_school
                            ];
                        return $r;
                    });
                    break;

                case 4: 
                    $collectPersonal['historyEmployment'] = $user->employmentHistory;
                    break; 

                default:

                    return 'undefined type personal document..';
                    break;
            }
        }

        // если стоит галочка "Требуется подтверждение о смене ФИО"
        $collectPersonal["nameReplacements"] = CourseTrait::needChangeSurname($course_id) ? $user->nameReplacements : null;
        return $collectPersonal;
    }

    public function formationOthers($other_types, $user)
    {
        return OtherDoc::whereIn('type_id', $other_types)->where('user_id', $user->id)->get();
    }

    public function formationDiploms($diploms, $user)
    {   
        $diplomsCollect = collect(HigherEdu::find($diploms));
        $userDiploms  = $diplomsCollect->map(function ($diplom) {
            $q = [
                "id" => $diplom->id,
                "level_edu" => HigherEduLevel::where('id', $diplom->level_education_higher_id)->first()->title,
                "speciality" => DiplomService::getParamsDiplom(
                    $diplom->level_education_higher_id, 
                    $diplom->speciality_id,
                    "speciality"
                ),
                "direction" => DiplomService::getParamsDiplom(
                    $diplom->level_education_higher_id, 
                    $diplom->direction_id,
                    "direction"
                ),
                    
            ];
            return $q;
        });
        return $userDiploms;
    }

    public function formationSpecializedSecondaryDiploms($diploms, $user)
    {   
        $diplomsCollect = collect(SpecializedSecondaryEdu::find($diploms));
        $userDiploms  = $diplomsCollect->map(function ($diplom) {
            $q = [
                "id" => $diplom->id,
                "level_edu" => "Среднеспециальное образование",
                "speciality" => DiplomService::getParamsSpecializedSecondaryDiplom(
                    $diplom->speciality_id,
                    "speciality"
                ),
                "direction" => DiplomService::getParamsSpecializedSecondaryDiplom(
                    $diplom->direction_id,
                    "direction"
                ),
                    
            ];
            return $q;
        });
        return $userDiploms;
    }

    public function searchByWord($title, $course_ids, $user)
    {   
        $simbolSearchCount = 3; // к-во символов при котором начинается поиск
        
        $result = Course::where('name' , 'LIKE', '%' . $title . '%')->get();
        $overlap = array_intersect($course_ids, $result->pluck('id')->toArray());

        if(strlen($title) >=  $simbolSearchCount) {
            return Order::whereIn('course_id', $overlap)->where('user_id', $user->id);
        } else {
            return Order::where('user_id', $user->id);
        }
    }


    // ВСЕ МЕТОДЫ НИЖЕ ТОЛЬКО ДЛЯ ЗАЯВОК ЮР. ЛИЦ

    public function orderBusinessCancelled($businessOrder)
    {   
        $businessOrder->status_id = $this::STATUS_CANCELLED;
        $businessOrder->save();
        return BusinessOrderResource::make($businessOrder);
    }

    // при создании заявки пересчитаем цену
    public function formationPriceBusiness($arr_courses)
    {   $packets = collect();
        foreach ( $arr_courses as $index => $courses) {
                $packets = collect();
                foreach ($courses as $index =>  $course) {
                    $packets[$index] = [
                        'id' => $course['packet_id'],
                        'count' => count($course['students'])
                    ];
                }
        }
        return $this->priceBusiness($packets->toArray()) ;
    }

    // получение цены в зависимости от выбранных параметров ( динамично на фронте)
    public function priceBusiness($packets)
    {   
        $price = 0;
        $ranges = collect();
        foreach ($packets as $packet) {
            $pack = Packet::find($packet['id']);
            if($pack->saleRules->isNotEmpty()){
                $f = $pack->saleRules->whereBetween('count', [0, $packet['count']])->sortBy('count')->last();
                if(!empty($f)) {
                    $price += $f->price * $packet['count'];
                } else {
                    $price += $pack->default_price * $packet['count'];
                }
            } else {
               $price += $pack->default_price * $packet['count'];
            }
        }
        return $price;
    }


    public function studentsDocuments($order) 
    {
        $listenersCourses = $order->append('orderBody')->order_body['courses'];
        foreach ($listenersCourses as $index => $course) {   
           $course_id = $course['id'];
           $students[$course_id] = $course['students'];
        }
        return $students;
    }

    public function listBusinessOrders($type, $company_id)
    {   

        $orders = BusinessOrder::all();
        $ids = [];
        foreach ($orders as  $order) {
            // $author = $order->append('orderBody')->order_body['author'];
            $author = $order->append('author')->author['author'];
            if($author['type'] == $type && $author['company_id'] == $company_id) {
                array_push($ids, $order->id);
            }
        }
        return ListBusinessOrderResource::collection(BusinessOrder::find($ids));
    }

    public static function countStudentsInOrder($business_order_id)
    {   
        $order = BusinessOrder::find($business_order_id);
        $courses = $order->append('orderBody')->order_body['courses'];
        $countStudents = 0;
        foreach ($courses as $course) {
            $countStudents += count($course['students']);
        }
        return $countStudents;
    }

    public static function minMaxDateOrder($business_order_id)
    {
        $order = BusinessOrder::find($business_order_id);
        $courses = $order->append('orderBody')->order_body['courses'];
        $flows_ids = [];

        foreach ($courses as $course) {
            array_push($flows_ids, $course['flow_id']);
        }

        $flows = Flow::find($flows_ids);
        $date['Max'] = collect($flows)->max('end');
        $date['Min'] = collect($flows)->min('start');
        return $date;
    }

    public function multiPay($order_ids, $packet_id, $flow_id)
    {   
        foreach ($order_ids as $index => $order_id) {
            $push_course = [];
            $coruses = [];
            $order = BusinessOrder::find($order_id);
            $courses = $order->append('orderBody')->order_body['courses'];
            $push_course = [
                'id' => FlowService::getCourseId($flow_id),
                'flow_id' => PacketService::getFlowId($packet_id),
                'students' => [],
                'packet_id' => $packet_id
            ];
            array_push($courses, $push_course);
            $courses = collect([
                'courses' => $courses
            ]);
            $order->order_body = $courses;
            $order->save();
            $notify[$index] = 'заявка #' . $order_id . ' успешно обновлена';
        }

        return $notify;
    }

    public function formationPersonalOrders($businessOrder)
    {
        $courses = $businessOrder->append('orderBody')->order_body['courses'];
        $author = $businessOrder->append('author')->author['author'];
        foreach ($courses as $index => $course) {
            if( count($course['students']) > 0) {
                foreach ($course['students'] as $profile_id) {
                    $user = User::find(Profile::getUserId($profile_id));
                    $this->newPersonalOrder($course, $user, $author, $businessOrder->id);
                }
            } 
        }
    }

    // TAB COURSES

    public function listCourses($business_order_id)
    {   
        $businessOrder = BusinessOrder::find($business_order_id);
        $courses = $businessOrder->append('orderBody')->order_body['courses'];
        $perPage = 5; // количество элементов на странице
        $page = LengthAwarePaginator::resolveCurrentPage(); // текущая страница
        $courseCollect = BusinessOrderCourseResource::collection($courses);
        $paginator = new LengthAwarePaginator(
            $courseCollect->forPage($page, $perPage)->values(), 
            $courseCollect->count(), 
            $perPage, 
            $page, 
            ['path' => request()->url()]
        );

        return compact('paginator');
    }

    // удаление курса (потока) полностью вместе со студентами
    public function deleteCourse($flow_id, $businessOrder)
    {   
        $studentsRemove = $this->orderValidateAction->updateBusinessOrder($businessOrder, $flow_id, 'removeFlow');
        foreach ($studentsRemove as $student_id) {
            $this->deletePersonalOrder($flow_id, $businessOrder->id, $student_id);
        }
        return $this->listCourses($businessOrder->id);
    }

    // слушатели внутри потока
    public function listenersIntoCourse($flow_id, $businessOrder)
    {   
        $this->flow_id = $flow_id;
        $this->businessOrder = $businessOrder;
        $courses = $this->businessOrder->append('orderBody')->order_body['courses'];
        $students = [];
        foreach ($courses as $course) {
            if($course['flow_id'] == $flow_id) {
                $this->course = $course;
                foreach ($course['students'] as $student) {
                    array_push($students, $student);
                }
            }
        }
        $students = collect($students);
        $studentsCollection = $students->map(function ($pId, $key) {
            $studentOrderId = $this->getOrderIdForStudent(Profile::getUserId($pId), $this->flow_id, $this->businessOrder->id);
            $studentOrder = Order::find($studentOrderId);

            // докм на зачисление
            $docs = \DB::table('admission_documents')->where('order_id', $studentOrderId)->pluck('id');
            $admissions = AdmissionDocumentResource::collection(AdmissionDocument::find($docs)); 

            return $studentsCollection[$key] = collect([
                'profile_id' => $pId,
                'full_name' => EmployeeService::fioProfile($pId),
                'order_id' => $studentOrderId,
                'status_documents' => $this->orderSubmission->documentStatusForStudent($studentOrder),
                'chat_room_id' => $this->getChatRoomIdForStudent($this->businessOrder->id, $pId ) ?? null,
                'admissions' => $admissions


            ]); 
        });

        // dd($course);
        $response['course'] =  BusinessOrderCourseResource::make($this->course);
        $response['students'] = $studentsCollection;
        return $response;
    }


    // создание заявок для студентов при создании бизнес заявки
    public function newPersonalOrder($course, $user, $author, $business_order_id)
    {   
        // т.к нет отдельной страницы для подачи заявки в юр. лице
        // мы передаем пустые документы, возможно следует передавать вообще все существующие
        $empty_docs_user = collect([
            'snils_id' => [],
            'higher_id' => ["empty"],
            "school_id" => [],
            "passport_id" => [],
            "secondary_id" => [],
            "other_documents" => [],
            "employmentHistory_id" => []

        ]);

        $modelCourse = Course::find($course['id']);
        $status =  $this::STATUS_CONSULTATION;
        $packet = Packet::find($course['packet_id']);
        $price = $packet->default_price;
        $order = new Order;
        $order->order_status_id = $status;
        $order->user_id = $user->id;
        $order->price = $price; 
        $order->course_id = $course['id'];
        $order->flow_id  = $course['flow_id'];
        $order->packet_id = $course['packet_id'];
        $order->business_order_id = $business_order_id;
        $order->save();
        // сформируем документы на зачисление
        $this->orderSubmission->createAdmissionDocuments($modelCourse, $order->id, $user, $empty_docs_user);
        
        // чаты
        $this->orderValidateAction->shouldCreateChat(Profile::getProfileId($user->id), $business_order_id);
    }



    // получить ид ордера для конкретного студента конрктеного потока ( курса)
    public function getOrderIdForStudent($user_id, $flow_id, $business_order_id)
    {   
        return \DB::table('orders')->where('flow_id', $flow_id)->where('user_id', $user_id)
            ->where('business_order_id', $business_order_id )->first()->id;
    }

    // получить чат рум студента с юр. лицом (1 на 1)
    public function getChatRoomIdForStudent($business_order_id, $studentId)
    {   
        $chatDb = \DB::table('chat_rooms')
                ->where('type_room', $this->orderValidateAction::BUSINESS_CHAT_ONE_TO_ONE)
                ->where('business_order_id', $business_order_id)
                ->whereJsonContains('profiles', [$studentId])->get();
        $roomId = ($chatDb->count() > 0) ? $chatDb->first()->id : 0;
        return $roomId; 
    }

    // добавить студентов в заявку со статусом подана
    public function createStudents($flow_id, $students, $businessOrder)
    {   
        $courses = $businessOrder->append('orderBody')->order_body['courses'];
        $author = $businessOrder->append('author')->author['author'];
        foreach ($courses as $index => $course) {
            if($course['flow_id'] == $flow_id) {
                foreach ($students as $student) {
                    array_push($course['students'] , $student);
                }
                $course['students'] = collect($course['students'])->unique()->values();
                // ?
                $courses[$index] = $course;
                $courseUpdate = $course;
            }

        }
        // 0. меняем сам ордер
        $businessOrder->order_body = collect([
            'courses' => $courses
        ]);
        $businessOrder->save();

        // dd($courseUpdate['flow_id
        // 1. апдейтим сущшности новых студетов ( заявки/документы/чаты) 

        foreach ($students as $student_id) {
            $user = User::find(Profile::getUserId($student_id));
            // перед созданием нового ордера убедимся что этого студента ещё не записывали на этот поток
            if($this->orderValidateAction->canCreateOrder($user, $courseUpdate['flow_id'], $businessOrder->id )){
                $this->newPersonalOrder($courseUpdate, $user, $author, $businessOrder->id);
            } else {
                return response()->json([
                    'message' => 'нельзя добавить этого студента на этот поток, возможно он уже добавлен вами или другой компанией..',
                    'code' => 403
                ], 200);
            }
        }
        // 2. формируем новый список с учетом всех дополнений
        return $this->listenersIntoCourse($flow_id, $businessOrder);
    }

    // выгнать студента с потока
    public function deleteStudentCourse($student_id, $flow_id, $businessOrder)
    {
        $courses = $businessOrder->append('orderBody')->order_body['courses'];
        foreach ($courses as $index => $course) {
            if($course['flow_id'] == $flow_id) {
                foreach ($course['students'] as $profileIndex => $profile_id) {
                    if($profile_id == $student_id){
                        $this->removeKey = $profileIndex;
                    }
                }
                if (isset($this->removeKey)) {
                    $courseStudents = collect($course['students'])->reject(function (int $value, int $key) {
                        if($key == $this->removeKey) {
                            return $value;
                        }
                    });
                    $course['students'] = $courseStudents->unique();
                    $courses[$index] = $course;
                    $courseUpdate = $course;
                }
            }
        }

        $businessOrder->order_body = collect([
            'courses' => $courses
        ]);
        $businessOrder->save();

        // Удалим заявку студента на этот поток
        $this->deletePersonalOrder($flow_id, $businessOrder->id, $student_id);

        // dd($businessOrder->id);
        return $this->listenersIntoCourse($flow_id, $businessOrder);
    }


    //  удаляем сущность персональной заявки ( с доками на зачисленеи)
    public function deletePersonalOrder($flow_id, $business_order_id, $studentId)
    {   
        $order_db = \DB::table('orders')
            ->where('flow_id', $flow_id)
            ->where('business_order_id', $business_order_id)
            ->where('user_id', Profile::getUserId($studentId))
            ->first();

        if($order_db != null) {
            $personalOrder = Order::find($order_db->id);
            // сносим документы на зачисление студента
            $personalOrder->admissionDocuments()->delete();
            // сносим чат (или нет)
            $this->orderValidateAction->shouldDeleteChat($studentId, $business_order_id);
            // сносим ордер студента
            $personalOrder->delete();
            // return $this->listenersIntoCourse($flow_id, $business_order_id);
        } else {
            return response()->json([
                "message" => "Невозможно удалить заявку студента, возможно онна была удалена ранее..",
                "code" => 403
            ],200);
        }
    }


    public function createCourseBusinessOrder($businessOrder, $course_id, $flow_id, $packet_id)
    {
        $courses = $businessOrder->append('orderBody')->order_body['courses'];

        $newCourse = collect([
            'id' => $course_id,
            'flow_id' => $flow_id,
            'students' => [],
            'packet_id' => $packet_id
        ]);

        $courses = collect($courses)->push($newCourse);
        // array_push($courses)
        
        $updateCourses = collect([
            'courses' => $courses
        ]);

        $businessOrder->order_body = $updateCourses;
        $businessOrder->save();

        return BusinessOrderResource::make($businessOrder);
    }

    // TAB STUDENTS INFO
    public function listenersForBusinessOrder($businessOrder, $searchWord)
    {
        if($businessOrder == null) {
            return response()->json([
                "message" => "Такой заявки не существует..проверьте параметры",
                "code" => 404
            ],404);
        }

        $listenersCourses = $businessOrder->append('orderBody')->order_body['courses'];
        foreach ($listenersCourses as $index => $course) {   
           $students[$index] = $course['students'];
        }

        $studentsProfiles = collect($students)->collapse()->unique();
        $studentsData = $businessOrder->personalOrders->map(function($order) {
            $docs = \DB::table('admission_documents')->where('order_id', $order->id)->pluck('id');
            $order->admissions = AdmissionDocumentResource::collection(AdmissionDocument::find($docs)); 
            $order->course_data = ListFlowsStudentsResource::make(Flow::find($order->flow_id));
            $order->status_documents = $this->orderSubmission->documentStatusForStudent($order);
            return $order;
        });

        // return $studentsData;
        $resp = $studentsData->groupBy('user_id');

        $m = collect();
        foreach ($resp as $userId => $order) {
            $profileId = Profile::getProfileId($userId);

            if(stristr(EmployeeService::fioProfile($profileId), $searchWord)) {

                $n = collect([
                    'student_id' => $profileId,
                    'full_name' => EmployeeService::fioProfile($profileId),
                    'data' => $order->values()
                ]);
                $m->push($n);
            }


        }

        // почему не сработало ?
        // if(strlen($searchWord) >=  3) {
        //     collect($m)->filter(function ($item) use ($searchWord) {
        //         return false !== stristr($item['full_name'], $searchWord);
        //     });
        // }

        $perPage = 2; // количество элементов на странице
        $page = LengthAwarePaginator::resolveCurrentPage(); // текущая страница
        $paginator = new LengthAwarePaginator(
            $m->forPage($page, $perPage)->values(), 
            $m->count(), 
            $perPage, 
            $page, 
            ['path' => request()->url()]
        );

        return compact('paginator');

    }

    public function deleteStudentEntire($studentId, $businessOrder)
    {   
        $userId = Profile::getUserId($studentId);
        $orders = $businessOrder->personalOrders->where('user_id', $userId);
        foreach ($orders as $indexOrder => $order) {
            // 0. сносим документы на зачисление студента
            $order->admissionDocuments()->delete();
            // 1. сносим чат (или нет)
            $this->orderValidateAction->shouldDeleteChat($studentId, $businessOrder->id);
            // 2.сносим ордер студента
            $order->delete();
            
        }
        // 3. апдейтим бизнес ордер
        $courses = $businessOrder->append('orderBody')->order_body['courses'];
        foreach ($courses as $indexCourse => $course) {
            foreach ($course["students"] as $index => $student_id) {
                if($student_id == $studentId) {
                    $this->removeKey = $index;
                }
            }
            if (isset($this->removeKey)) {
                $courseStudents = collect($course['students'])->reject(function (int $value, int $key) {
                    if($key == $this->removeKey) {
                        return $value;
                    }
                });
                $courses[$indexCourse]['students'] = $courseStudents->values()->toArray();
            }
        }
        $businessOrder->order_body = collect([
            'courses' => $courses
        ]);
        $businessOrder->save();
        // обратно покажем странциу слушателей
        return $this->listenersForBusinessOrder($businessOrder);
    }

    public function businessCoursesFilters($params)
    {
        $businessOrder = BusinessOrder::find($params['business_order_id']);
        // изначальный массив курсов в заявке
        $courses = $businessOrder->append('orderBody')->order_body['courses'];
        $coursesStudents = $businessOrder->append('orderBody')->order_body['courses'];
        $directions = CategoryCourse::whereIn('id', $params['directions'])->get();
        $this->typeRetraining = CategoryCourse::where('slug', $this::RETRAINING)->pluck('id');
        $this->typeRequalification = CategoryCourse::where('slug', $this::REQUALIFICATION)->pluck('id');
       
        $specialities = CategoryCourse::specialities()->whereIn('id', $params['specialities'])->get();
        $this->study_forms = $params['study_forms'];
        $this->countStudentsFrom = $params['count_students']['from'];
        $this->countStudentsTo = $params['count_students']['to'];
        $this->startDates = $params['dates'];
        $this->typeForms = $params['study_types'];

        $this->uniqueCourseIdsDirections = collect();
        $this->uniqueCourseIdsSpecialities = collect();
        $this->uniqueCourseIdsStudyForms = collect();
        $this->uniqueCourseIdsCounts = collect();
        $this->uniqueCourseIdsDates = collect();
        $this->uniqueCourseIdsTypeForms = collect();

        //dirs
        foreach ($directions as $index => $direction) {
            $direction->courses->map(function($course){
                $this->uniqueCourseIdsDirections->push($course->id);
            });
        }

        //spec
        foreach ($specialities as $index => $speciality) {
            $speciality->courses->map(function($course){
                $this->uniqueCourseIdsSpecialities->push($course->id);
            });
        }
        // study forms
        foreach ($courses as $index => $course) {
            $flow = Flow::find($course['flow_id']);
            if( in_array($flow->type_id, $this->study_forms) ) {
                $this->uniqueCourseIdsStudyForms->push($course['id']);
            }
        }
        // counts
        collect($courses)->map(function($course){
            if(count($course['students']) >= $this->countStudentsFrom && count($course['students']) <= $this->countStudentsTo) {
                $this->uniqueCourseIdsCounts->push($course['id']);
            }
        });

        // dates
        collect($courses)->map(function($course){
            $flow = Flow::find($course['flow_id']);
            if(in_array($flow->start, $this->startDates)) {
                $this->uniqueCourseIdsDates->push($course['id']);
            }
        });
        // type forms
        collect($courses)->map(function($course){
            $course = Course::find($course['id']);
            if(!empty($this->typeForms)) {
                if( in_array(2, $this->typeForms)  &&  $this->typeRetraining->intersect($course->tree->flatten())->count() > 0 )  {
                    $this->uniqueCourseIdsTypeForms->push($course['id']);
                }
                if( in_array(1, $this->typeForms)  &&  $this->typeRequalification->intersect($course->tree->flatten())->count() > 0 )  {
                    $this->uniqueCourseIdsTypeForms->push($course['id']);
                }
            }
        });

        $intersectArr = [];
        if($this->uniqueCourseIdsSpecialities->isNotEmpty()) {
            $this->uniqueCourseIdsSpecialities = $this->uniqueCourseIdsSpecialities->unique()->values();
            array_push($intersectArr, $this->uniqueCourseIdsSpecialities);
        }

        if($this->uniqueCourseIdsDirections->isNotEmpty()) {
            $this->uniqueCourseIdsDirections = $this->uniqueCourseIdsDirections->unique()->values();
            array_push($intersectArr, $this->uniqueCourseIdsDirections);
        }

        if($this->uniqueCourseIdsStudyForms->isNotEmpty()) {
            $this->uniqueCourseIdsStudyForms = $this->uniqueCourseIdsStudyForms->unique()->values();
            array_push($intersectArr, $this->uniqueCourseIdsStudyForms);
        }

        if($this->uniqueCourseIdsCounts->isNotEmpty()) {
            $this->uniqueCourseIdsCounts = $this->uniqueCourseIdsCounts->unique()->values();
            array_push($intersectArr, $this->uniqueCourseIdsCounts);
        }

        if($this->uniqueCourseIdsDates->isNotEmpty()) {
            $this->uniqueCourseIdsDates = $this->uniqueCourseIdsDates->unique()->values();
            array_push($intersectArr, $this->uniqueCourseIdsDates);
        }

        if($this->uniqueCourseIdsTypeForms->isNotEmpty()) {
            $this->uniqueCourseIdsTypeForms = $this->uniqueCourseIdsTypeForms->unique()->values();
            array_push($intersectArr, $this->uniqueCourseIdsTypeForms);
        }

        if($intersectArr == []) {
            $resultArr = collect();
        }
        // magic
        foreach ($intersectArr as $index => $value) {

            if(isset($intersectArr[$index+1])) {
                if(isset($resultArr)) {
                    $resultArr = $resultArr->intersect($intersectArr[$index+1]);    
                } else {
                    $resultArr = $intersectArr[$index]->intersect($intersectArr[$index+1]);
                }
            } else {

                if(!isset($intersectArr[$index-1])) {
                    $resultArr = $intersectArr[$index];
                } else {

                    // $resultArr = [];
                }
            }
        }
        foreach ($courses as $index => $course) {
            if(!$resultArr->contains($course['id'])) {
                $this->index = $index;
                $courses = collect($courses)->reject(function ($course, $key) {
                    if($key == $this->index) {
                        return $course;
                    }
                });
            }    
        }
        $businessOrder->order_body = collect([
            'courses' => collect($courses)->values()
        ]);

        $courseCollect = BusinessOrderCourseResource::collection($courses);
        $perPage = 5;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $courseCollect->forPage($page, $perPage)->values(), 
            $courseCollect->count(), 
            $perPage, 
            $page, 
            ['path' => request()->url()]
        );

        return compact('paginator');
    }

    public function getDataCoursesFilters($business_order_id)
    {   
        $businessOrder = BusinessOrder::find($business_order_id);
        $courses = $businessOrder->append('orderBody')->order_body['courses'];
        $flowsDates = collect();
        foreach ($courses as $course) {
            $flow = Flow::find($course['flow_id']);
            $flowsDates->push($flow->start);
        }

        $this->counts = [0];
        collect($courses)->map(function($course){
            array_push($this->counts, count($course['students']));
        });

        $dataFilter = collect([
            'directions' => CategoryCourse::all(),
            'study_forms' => \DB::table('study_forms')->get(['id', 'title', 'slug']),
            // 'specialities' => CategoryCourse::specialities()->get(),
            'specialities' => CategoryCourse::specialities()->get(),
            'dates' => $flowsDates,
            'study_types' => [

                [
                    'id' => 1,
                    'title' => 'Переквалификация'
                ],

                [
                    'id' => 2,
                    'title' => 'Переподготовка'
                ]
            ],
            'count_students' => [
                'min' => collect($this->counts)->min(),
                'max' => collect($this->counts)->max()
            ]
        ]);
        return $dataFilter;
    }
}
