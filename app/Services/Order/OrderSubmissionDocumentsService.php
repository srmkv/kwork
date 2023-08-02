<?php
namespace App\Services\Order;
use App\Models\Order\AdmissionDocument;
use App\Models\Order;
use App\Models\Passport;
use App\Models\Snils;
use App\Models\HigherEdu;
use App\Models\SecondaryEdu;
use App\Models\SpecializedSecondaryEdu;
use App\Models\OtherDoc;
use App\Models\EmploymentHistory;

use App\Models\Course\NeededSpeciality;
use App\Models\Course\NeededPersonalDoc;
use App\Models\Course\NeedOtherType;
use App\Http\Resources\Course\MoreInformationDocEduResource;
use App\Services\Order\Document\ComparisonEdu;
use App\Services\Order\OrderPersonalDocService;
use App\Services\Order\OrderOtherDocService;
use App\Models\Order\ReservedDocument;

use App\Services\UserDocuments\StatusDocService;

class OrderSubmissionDocumentsService
{   
    const ADMISSION_ERROR = 'error';  // документ не прошел авто проверку
    const ADMISSION_ACCEPT = 'considiration'; // документ прошел автопроверку, ушел на модерацию к админам

    // типы документов которые отправляет пользователь на зачисление:

    const TYPE_PASSPORT = "passports";
    const TYPE_HIGHTER_DIPLOM = "higher_diploms";
    const TYPE_SCHOOL= "schools";
    const TYPE_SNILS = "snils";
    const TYPE_SECONDARY_DIPLOM = "secondary_diploms";
    const TYPE_OTHER_DOCUMENT = "other_documents";
    const TYPE_EMPLOYMENT_HISTORY = "employmentHistory";
    const TYPE_ADDITIONAL_DIPLOM = "additional_diploms";

    const USER_DOCUMENTS_NULL = [
        "snils_id" => [],
        "higher_id" => ["empty"],
        "school_id" => [],
        "passport_id" => [],
        "secondary_id" => [],
        "other_documents" => [],
        "employmentHistory_id" => [],
        "additional_diploms" => []
    ];
    

    public function __construct(
        ComparisonEdu $comparisonEdu,
        OrderPersonalDocService $personalDocument,
        OrderOtherDocService $otherDocument,
        StatusDocService $statusDoc
    )
    {
        $this->comparisonEdu = $comparisonEdu;
        $this->personalDocument = $personalDocument;
        $this->otherDocument = $otherDocument;
        $this->statusDoc = $statusDoc;
    }

    public function createAdmissionDocuments($course, $order_id, $user, $user_documents)
    {   
        // PERSONAL
        foreach ($course->neededPersonalDocs as $documentIndex => $needPersonal) {
            $admission = new AdmissionDocument;
            $admission->order_id = $order_id;
            $admission->title = $needPersonal->title;
            $admission->user_id = $user->id;
            $admission->type = "personal";
            $admission->user_documents = $user_documents ?? $this::USER_DOCUMENTS_NULL;
            $admission->need_documents = $this->fomationNeedPersonal($needPersonal->id);
            $admission->save();
            $this->formationAdmissionStatus($admission);
        }

        // OTHERS
        foreach ($course->needOtherTypes as $documentIndex => $needOther) {
            $admission = new AdmissionDocument;
            $admission->order_id = $order_id;
            $admission->title = $needOther->title;
            $admission->user_id = $user->id;
            $admission->type = "other";
            $admission->user_documents = $user_documents ?? $this::USER_DOCUMENTS_NULL;
            $admission->need_documents = $needOther->append('required_types')->required_types;
            $admission->save();
            $this->formationAdmissionStatus($admission);
        }

        // EDUCATION
        foreach ($course->neededSpecialities as $documentIndex => $needDocument) {
            $admission = new AdmissionDocument;
            $admission->order_id = $order_id;
            $admission->title = $needDocument->title;
            $admission->user_id = $user->id;
            $admission->type = "education";
            // на каждый необходимый адмишен проставим комплект дипломов
            // мы дублируем этот json, потому что необходимые документы 
            // могут измениться в курсе, но в заявке должны оставаться такими же как в момент подачи
            $admission->need_documents = $this->formationNeedEduDocuments($needDocument->id);
            // на каждый документ сформируем документы юзера, 
            // которые он передает во время формирования заявки, либо нулы если это коснультация
            $admission->user_documents = $user_documents ?? $this::USER_DOCUMENTS_NULL;
            $admission->save();
            // после того как документы разложили в бд, делаем финальную проверку
            // для формирования корректного статуса КОНКРЕТНОГО ADMISSION документа
            $this->formationAdmissionStatus($admission);
        }

    }

    // 
    public function formationAdmissionStatus($admission)
    {   
        $user_documents = $admission->append('user_documents')->user_documents;
        $need_documents = $admission->append('need_documents')->need_documents;

        $dataOrder = [
            'user_id' => $admission->user_id,
            'order_id' => $admission->order_id,
            'type_order' => "personal" // business ?,
        ];

        switch ($admission->type) {
            case 'education':
                if(!$this->comparisonEdu->correctionStatus($user_documents, $need_documents)) {
                    $admission->status = 'error';
                    $admission->save();
                } else {
                    $admission->status = 'considiration';
                    $admission->save(); 
                }
            break;
            case 'personal' :

                if($this->personalDocument->correctionStatus($user_documents, $need_documents)) {

                    $admission->status = 'considiration';
                    $admission->save();


                } else {
                    $admission->status = 'error';
                    $admission->save();
                }

            break;
            case 'other' :

                if(($this->otherDocument->correctionStatus($user_documents, $need_documents))){
                    $admission->status = 'considiration';
                    $admission->save();  
                    // $this->reservedUserDocuments($user_documents);
                    // $this->documentsCloning($user_documents, $dataOrder);
                } else {
                    $admission->status = 'error';
                    $admission->save();
                }
            break;
            default:
                return 'Такого type admission не существует..';
            break;
        } 

        // клонируем
        if(!empty($user_documents) && isset($user_documents)) {
            $this->documentsCloning($user_documents, $dataOrder);
        }
    }

    public function fomationNeedPersonal($document_id)
    {
        $need = NeededPersonalDoc::find($document_id);
        $edu_doc['array_documents'] = $need->append('required_docs')->required_docs;
        $edu_doc['other_type_docs'] = $need->append('other_type_docs')->other_type_docs;
        return $edu_doc;
    }

    public function formationNeedEduDocuments($document_id)
    {   
        $need = NeededSpeciality::find($document_id);
        $edu_doc['array_documents'] = $need->append('needed_edu_docs')->needed_edu_docs;
        $edu_doc['other_type_docs'] = $need->append('other_type_docs')->other_type_docs;
        return $edu_doc;
    }

    // Внутри юр. заявки получим документы студентов и т.п
    public function documentStatusForStudent($order)
    {   
        $order = Order::find($order->id);
        return collect([
            'count_all' => $order->admissionDocuments->count(),
            'count_error' => $order->admissionDocuments->where('status', $this::ADMISSION_ERROR)->count(),
            'count_accept' => $order->admissionDocuments->where('status', $this::ADMISSION_ACCEPT)->count(),
        ]);
    }

    // документы резервируем в случае если они ушли на проверку админу
    public function documentsCloning($user_documents, $dataOrder)
    {   
        $snilsIds = $user_documents['snils_id'];

        if(!in_array("empty", $user_documents['higher_id'])) {
            $higherDiplomsIds = $user_documents['higher_id'];
        } else {
            $higherDiplomsIds = [];
        }
        
        $schoolsIds = $user_documents['school_id'];
        $passportsIds = $user_documents['passport_id'];
        $secondaryDiplomsIds = $user_documents['secondary_id'];
        $otherDocumentsIds = $user_documents['other_documents'];
        $employmentHistoryIds = $user_documents['employmentHistory_id'];


        if(count($snilsIds) > 0 ) {
            $snils = Snils::find($snilsIds);
            $this->cloning($dataOrder, $snils, $this::TYPE_SNILS);
        }

        if(count($passportsIds) > 0 ) {
            $passports = Passport::find($passportsIds);
            $this->cloning($dataOrder, $passports, $this::TYPE_PASSPORT);
        }

        if(count($higherDiplomsIds) > 0) {
            $diploms = HigherEdu::find($higherDiplomsIds);
            $this->cloning($dataOrder, $diploms, $this::TYPE_HIGHTER_DIPLOM);
        }

        if(count($schoolsIds) > 0) {
            $schools = SecondaryEdu::find($schoolsIds);
            $this->cloning($dataOrder, $schools, $this::TYPE_SCHOOL);
        }

        if(count($secondaryDiplomsIds) > 0) {
            $secondaryDiploms = SpecializedSecondaryEdu::find($secondaryDiplomsIds);
            $this->cloning($dataOrder, $secondaryDiploms, $this::TYPE_SECONDARY_DIPLOM);
        }

        if(count($otherDocumentsIds) > 0) {
            $otherDocs = OtherDoc::find($otherDocumentsIds);
            $this->cloning($dataOrder, $otherDocs, $this::TYPE_OTHER_DOCUMENT);
        }

        if(count($employmentHistoryIds) > 0) {
            $historyBooks = EmploymentHistory::find($employmentHistoryIds);
            $this->cloning($dataOrder, $historyBooks, $this::TYPE_EMPLOYMENT_HISTORY);
        }   
    }

    public function cloning($dataOrder, $documents, $type)
    {   
        $reserve = ReservedDocument::firstOrCreate(
            [
                'order_body->user_id' => $dataOrder['user_id'],
                'order_body->order_id' => $dataOrder['order_id']
            ],

            ['order_body->type_order' => $dataOrder['type_order']]
        );


        $reservedDocuments =  $reserve->append('reserved_docs')->reserved_docs[$type];

        switch ($type) {
            case $this::TYPE_PASSPORT:
                $reserve->update(['reserved_docs->passports' => $documents ]);
                break;
            case $this::TYPE_HIGHTER_DIPLOM:
                $reserve->update(['reserved_docs->higher_diploms' => $documents ]);
                break;

            case $this::TYPE_SNILS:
                $reserve->update(['reserved_docs->snils' => $documents ]);
                break;

            case $this::TYPE_SECONDARY_DIPLOM:
                $reserve->update(['reserved_docs->secondary_diploms' => $documents ]);
                break;
            case $this::TYPE_OTHER_DOCUMENT:
                $reserve->update(['reserved_docs->other_documents' => $documents ]);
                break;
            case $this::TYPE_EMPLOYMENT_HISTORY:
                $reserve->update(['reserved_docs->employmentHistory' => $documents ]);
                break;

            case $this::TYPE_ADDITIONAL_DIPLOM:
                $reserve->update(['reserved_docs->additional_diploms' => $documents ]);
                break;
            default:
                return 'Появился новый тип документа?..';
                break;
        }

    }
}


