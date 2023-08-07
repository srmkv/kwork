<?php

namespace App\Http\Controllers\Api\Course\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Course\NeededSpeciality;
use App\Models\Course\NeedOtherType;

use App\Models\Course\Course;
use App\Models\Course\Packet;
use App\Models\User;
use App\Models\Order;
use App\Models\Order\AdmissionDocument;
use App\Models\Order\FlowDocument;

use App\Services\Order\OrderSpecialityService;
use App\Services\Order\OrderPersonalDocService;
use App\Services\Order\OrderOtherDocService;
use App\Services\Order\OrderCommonService;
use App\Services\Order\OrderSubmissionDocumentsService;


class OrderController extends Controller
{   

    public function __construct(
        OrderSpecialityService $orderSpeciality,
        OrderPersonalDocService $orderPersonalDocument,
        OrderOtherDocService $orderOtherDocument,
        OrderCommonService $orderService,
        OrderSubmissionDocumentsService $orderSubmission,
    )
    {
        $this->orderSpeciality = $orderSpeciality;
        $this->orderPersonalDocument = $orderPersonalDocument;
        $this->orderOtherDocument = $orderOtherDocument;
        $this->orderService = $orderService;
        $this->orderSubmission = $orderSubmission;

    }


    public function newOrder(Request $request)
    {
        $user = User::find(Auth::id());
        $course = Course::find($request->course_id);
        $status = $request->status_id;
        $packet = Packet::find($request->packet_id);
        $price = $packet->default_price;
        $order = new Order;
        $order->order_status_id = $status;
        $order->user_id = $user->id;
        $order->price = $price; 
        $order->course_id = $course->id;
        $order->flow_id  = $request->flow_id;
        $order->packet_id = $request->packet_id; 
        
        $order->save();

        // сформируем документы на зачисление
        $this->orderSubmission->createAdmissionDocuments($course, $order->id, $user, $request->user_documents);
        // сформируем выдаваемые дипломы и их первичные статусы
        // $this->orderService->flowDocuments($course, $order->id);
        return $order;
        
    }

    public function getStatusesOrder(Request $request)
    {
        return \DB::table('order_statuses')->get();
    }


    public function documentFlow(Request $request)
    {   

        $user = User::find(Auth::id());
        $order = Order::find($request->order_id);
        $course = Course::find($order->course_id);
        return FlowDocument::where('order_id', $order->id)->get();
    }

    public function programmOrder(Request $request)
    {   
        return $this->orderSubmission->formationAdmissionStatus(AdmissionDocument::find(123));
    }


    public function getOrders(Request $request)
    {   
        $user = User::find(Auth::id());
        $sort = $request->sort;

        $itemsPerPage = 4; // к-во ордеров на странице
        

        $title = $request["query"];
        $course_ids = [];
        $orders = $user->orders;
        foreach ($orders as $order) {
            array_push($course_ids, $order->course_id);
        }

        switch ($sort) {
            case 'price-asc':
                return $this->orderService->searchByWord($title, array_unique($course_ids), $user)->orderBy('price', 'asc')->paginate($itemsPerPage);
            break;
            
            case 'price-desc' :
                return $this->orderService->searchByWord($title, array_unique($course_ids), $user)->orderBy('price', 'desc')->paginate($itemsPerPage);
            break;

            case 'date-asc' :
                return $this->orderService->searchByWord($title, array_unique($course_ids), $user)->orderBy('created_at', 'asc')->paginate($itemsPerPage);
            break;

            case 'date-desc':
                return $this->orderService->searchByWord($title, array_unique($course_ids), $user)->orderBy('created_at', 'desc')->paginate($itemsPerPage);
            break;

            default:
                return $this->orderService->searchByWord($title, array_unique($course_ids), $user)->orderBy('created_at', 'desc')->paginate($itemsPerPage);
            break;
        }

    }



    public function getOrderById(Request $request, $order_id)
    {
        $user = User::find(Auth::id());
        $order = Order::find($order_id);
        return collect($order)->except(['user_documents']);
    }

    // отдельный поиск (freeze)
    public function searchOrders(Request $request)
    {
        $user = User::find(Auth::id());
        $orders = $user->orders;
        $title = $request["query"];        
        $course_ids = [];
        foreach ($orders as $order) {
            array_push($course_ids, $order->course_id);
        }

        return $this->orderService->searchByWord($title, array_unique($course_ids), $user);
    }


    // ВСЕ ЧТО НИЖЕ ДЛЯ СТРАНИЦЫ ПОДАЧИ ЗАЯВКИ ( ВЫНЕСТИ)
    public function checkOrder(Request $request) 
    {   

        $collectNeedsOther = [];
        $user = User::find(Auth::id());
        $course = Course::find($request->course_id);

        $this->orderSpeciality->superResponse = [

            "backelor" => [],
            "master" => [],
            "specialitet" => [],
            "residency" => [],
            "postgraduate" => [],
            "assistant_intership" => [],
            "specialized_secondary" => [],

        ];

        $crutchPress = 0;

        foreach ($course->neededSpecialities as $indexDocument => $speciality_document) {
            $need_edu_docs_db = NeededSpeciality::find($speciality_document->id);
            // массив самих дипломов
            $need_edu_docs =  $need_edu_docs_db->append('needed_edu_docs')->needed_edu_docs;
            // массив дополнительных документов
            $need_other_types = $need_edu_docs_db->append('other_type_docs')->other_type_docs;

            $descriptionDocument =  $need_edu_docs_db->description;
            $documentId = $need_edu_docs_db->id;

            // пройдемся по множеству специальностей внутри ОБРАЗОВТЕЛЬНОГО документа
            foreach ($need_edu_docs as $docIndex => $edu_doc) {
                $level_edu = $edu_doc['name'];
                    array_push($this->orderSpeciality->superResponse[$level_edu], $this->orderSpeciality->diplomsComparison($course, $user, $edu_doc, $documentId, $need_other_types, $docIndex, $crutchPress));
            }

            // пройдемся по дополнительным документам ( которые могут заменить документы выше)
            $types_ids_other = $this->orderSpeciality->checkOtherTypeDocument($need_other_types, $user->otherDocuments );
            $status_other_doc = $types_ids_other ? "matched" : "unmatched";

            $this->orderSpeciality->superResponse["other_documents"][$indexDocument] = [
                "status" => $status_other_doc,
                "document_id" => $speciality_document->id,
                "types_ids" => $types_ids_other
            ];
        }

        $arr["personal"] = $this->personalCheck($request);
        $arr["education"] = $this->orderSpeciality->superResponse;
        $arr["other"] = $this->otherCheck($request);

        return collect($arr)->only(["education", "personal", "other"]);
    }


    public function personalCheck(Request $request)
    {
        $user = User::find(Auth::id());
        $course = Course::find($request->course_id);
        $user_other_docs = $user->otherDocuments; 
        $unmatched = [];
        $response_personal_doc = [];

        foreach ($course->neededPersonalDocs as $indexPersonal => $personalNeed) {
            $id = $personalNeed->id;
            $need_docs_personal = $personalNeed->append('required_docs')->required_docs;
            $need_docs_other = $personalNeed->append('other_type_docs')->other_type_docs;
            $type_other_check = $this->orderOtherDocument->checkOtherTypeDocument($need_docs_other, $user_other_docs);
            $status_other_doc = $type_other_check ? "matched" : "unmatched";
            $personalResponse["other_documents"][$indexPersonal] = [
                "status" => $status_other_doc,
                "document_id" => $personalNeed->id,
                "other_types_ids" => $type_other_check
            ];
            // массив сравнения внутри одного массива личного документа
            $response_personal_doc[$indexPersonal]["main_doc"] = collect($this->orderPersonalDocument->getResult($need_docs_personal, $user, $id));
            // массив сравнения доп. документов внутри массива личного документа
            $response_personal_doc[$indexPersonal]["other_doc"] = collect($personalResponse["other_documents"][$indexPersonal]);
        }
        // dd($course->neededPersonalDocs->count());
        // если нет документов персональных в курсе
        // if($course->neededPersonalDocs->count() == 0) {

        //     $response_personal_doc[$indexPersonal]["main_doc"] = collect($this->orderPersonalDocument->getResult([], $user, 'no id'));

        // }

        return collect($response_personal_doc);
    } 

    // Проверка дополнительных документов
    public function otherCheck(Request $request)
    {
        $user = User::find(Auth::id());
        $course = Course::find($request->course_id);
        $user_other_docs_arr = $user->otherDocuments->pluck('type_id')->toArray(); 

        $orderOther = collect();
        // необходимые доп. документы нужные в курсе
        $need_other_types_documents = $course->needOtherTypes;

        //внутри каждого доп. документа есть пачка других доп. документов.. проверяем её..
        foreach ($need_other_types_documents as $indexOtherDocument => $need_other_document) {
            $arrOther = $need_other_document->append('required_types')->required_types;
            $otherResponse = [];

            $otherDocumentsContainsNeed = !empty(array_intersect($arrOther, $user_other_docs_arr));

            if(!$otherDocumentsContainsNeed) {
                $otherResponse["unmatched"] = [
                    "status" => "unmatched", 
                    "document_id" => $need_other_document->id
                ];
            } elseif ($otherDocumentsContainsNeed) {
                $otherResponse["matched"] = [

                    "status" => "matched", 
                    "document_id" => $need_other_document->id
                ];
            } 

            $resp[$indexOtherDocument] = $otherResponse;

        }

        return $resp ?? [];

    }

    
    public function matchOrder(Request $request, $course_id)
    {   $user = User::find(Auth::id());
        

        $checkOrder = $this->checkOrder($request);
        $personalsMatch =  $checkOrder["personal"];
        $educationsMatch =  $checkOrder["education"];
        $othersMatch =  $checkOrder["other"];


        $matchOrder = [
            "personal" => [],
            "education" => [],
            "other" => []
        ];

        $user_documents = [
            "personal" => [],
            "education" => [

                "higher" => [],
                "specialized_secondary" => []

            ],
            "others" => []
        ];

        $personalTypes = [];
        $otherTypes = [];
        $diploms = [];
        $specializedSecondaryDiploms = [];

        // ЛИЧНЫЕ
        foreach ($personalsMatch as $indexPersonalMatch => $personalUser) {
            $main_doc_matches =  $personalsMatch[$indexPersonalMatch]["main_doc"];
            $other_matches =  $personalsMatch[$indexPersonalMatch]["other_doc"];
            // фомируем ид персональных документов которые "прошли" проверку
            // среди основных
            foreach ($main_doc_matches as $match) {
                if ($match["status"] == "matched"){
                    // какие типы документов отобразим для выбора пользователем
                    array_push($personalTypes, $match["personal_type"]);
                    if (!in_array( $match["document_id"], $matchOrder["personal"])){
                        array_push($matchOrder["personal"], $match["document_id"] );
                    }
                } 
            }
            // среди дополнительных
            if( $other_matches["status"] == "matched" )  {
                $otherTypes = array_unique(array_merge($other_matches["other_types_ids"], $otherTypes ));
                if (!in_array($other_matches["document_id"], $matchOrder["personal"]) ) {
                    array_push($matchOrder["personal"], $other_matches["document_id"] );
                }                
            }
        }

        // ОБРАЗОВАНИЕ
        foreach ($educationsMatch as $typeEdu => $educationUser) {

            foreach ($educationUser as $match) {
                // ловим среднеспециальное
                if(count($match) > 0 && ($typeEdu == "specialized_secondary")  &&(
                        $match["status"] == "matched_specialities" ||  
                        $match["status"] == "matched_directions" ||  
                        $match["status"] == "matched_any_all" )) {

                    $specializedSecondaryDiploms = array_unique(array_merge($match["user_diploms"]->toArray(), $specializedSecondaryDiploms ));
                    if (!in_array($match["document_id"], $matchOrder["education"])){
                        array_push($matchOrder["education"], $match["document_id"] );
                    } 
                }

                //  высшее образование
                if(count($match) > 0 && ($typeEdu != "other_documents" && $typeEdu != "specialized_secondary")  &&(
                        $match["status"] == "matched_specialities" ||  
                        $match["status"] == "matched_directions" ||  
                        $match["status"] == "matched_any_all" )) {
                    $diploms = array_unique(array_merge($match["user_diploms"]->toArray(), $diploms ));

                    if (!in_array($match["document_id"], $matchOrder["education"])){
                        array_push($matchOrder["education"], $match["document_id"] );
                    } 
                }
                // заменяющие дипломы ( прочие документы)
                if(count($match) > 0 && ($typeEdu == "other_documents")  && ($match["status"] == "matched" )) {
                    if( !in_array($match["document_id"], $matchOrder["education"])) {
                        array_push($matchOrder["education"], $match["document_id"] );
                    }
                    $otherTypes = array_unique(array_merge($match["types_ids"], $otherTypes ));
                } 
            }
       }

    
        // ПРОЧИЕ )
        foreach ($othersMatch as $index => $otherMatch) {

            foreach ($otherMatch as $match) {   
                if ($match["status"] == "matched") {
                    if(!in_array($match["document_id"], $matchOrder["other"] )) {
                        array_push($matchOrder["other"], $match["document_id"] );
                    }

                    if(!in_array($match["document_id"], $otherTypes )) {
                        array_push($otherTypes, $match["document_id"] );
                    }                   
                }
            }
       }
       // массив с персональными доками
       $user_documents["personal"] = $this->orderService->formationPersonal($personalTypes, $user, $course_id);
       // массив с прочими доками
       $user_documents["others"] = $this->orderService->formationOthers($otherTypes, $user);
       // массив с дипломами ( вышки)
       $user_documents["education"]["higher"] = collect($this->orderService->formationDiploms($diploms, $user));
       // массив с дипломами ( среднеспециальное )
       $user_documents["education"]["specialized_secondary"] = collect($this->orderService->formationSpecializedSecondaryDiploms($specializedSecondaryDiploms, $user));
       $final = [];
       $final["matched"] = $matchOrder;
       $final["user_documents"] = collect($user_documents);
       return $final;
    }



    public function userCheckedDocuments(Request $request, $course_id)
    {
        $user = User::find(Auth::id());
        $course = Course::find($request->course_id);
        $user_documents = $request->user_documents;
        $need_other_types_documents = $course->needOtherTypes;
        $matchOrder = [
            "personal" => [],
            "education" => [],
            "other" => []
        ];
        $matched = [];
        // по этому костылю определим, что сравниваем только выбранные юзером документы
        $crutchPress = $user_documents["higher_id"];
        foreach ($course->neededSpecialities as $indexDocument => $speciality_document) {
            $need_edu_docs_db = NeededSpeciality::find($speciality_document->id);
            // массив самих дипломов
            $need_edu_docs =  $need_edu_docs_db->append('needed_edu_docs')->needed_edu_docs;
            // массив дополнительных документов
            $need_other_types = $need_edu_docs_db->append('other_type_docs')->other_type_docs;
            $documentId = $need_edu_docs_db->id;
            foreach ($need_edu_docs as $docIndex => $edu_doc) {
                if($edu_doc["name"] == "specialized_secondary") {
                       $crutchPress = $user_documents["secondary_id"];
                }
                $level_id = $this->orderSpeciality->checkLevel($edu_doc['name']);
                $directions = $edu_doc['directions'];
                $specialities = $edu_doc['specialities'];
                $need_other_types = [];
                
                if($this->orderSpeciality->diplomsComparison($course, $user, $edu_doc, $documentId, $need_other_types, $docIndex, $crutchPress)) {
                    array_push($matched, $documentId );
                    // 2. добавлять, если совпали по прочим докам.. todo..

                } 
            }

        }

        //PERSONAL
        foreach ($course->neededPersonalDocs as $indexPersonal => $personalNeed) {
            $id = $personalNeed->id;
            $need_docs_personal = $personalNeed->append('required_docs')->required_docs;
            $need_docs_other = $personalNeed->append('other_type_docs')->other_type_docs;
            if($this->orderPersonalDocument->getResultChecked($need_docs_personal, $user_documents, $id)){

                if(!in_array($id, $matchOrder["personal"] )) {
                    array_push($matchOrder["personal"], $id );
                }
            }
        }

        // OTHER 
        foreach ($need_other_types_documents as $indexOtherDocument => $need_other_document) {
            $arrOther = $need_other_document->append('required_types')->required_types;
            $otherResponse = [];

            // находим ид справки которые совпала для документа 
            $match = \DB::table('other_docs')->where('user_id', $user->id)->whereIn('type_id', $arrOther)->pluck('id');

            $diff = array_intersect($match->toArray(), $user_documents["other_documents"]);
            if(count($diff) > 0) {
                if(!in_array($need_other_document->id, $matchOrder["other"] )) {
                    array_push($matchOrder["other"], $need_other_document->id );
                }

            }
        }

        $matchOrder["education"] = $matched;

        return $matchOrder;

    }    

}


