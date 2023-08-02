<?php
namespace App\Services\Order;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\Course\NeededPersonalDoc;
use App\Models\OtherDoc;

class OrderPersonalDocService
{   
    public function getResult($need_docs_personal, $user,$id)
    {
        // 1   Паспорт
        // 2   СНИЛС
        // 3   Среднее образование
        // 4   Трудовая
        // 5   Дополнительное образование ( rudiment)
        $successArr = [];
        $personalResponse = [];
        // if($n)
        // dd($need_docs_personal);
        if(!is_null($need_docs_personal)) {
            foreach ($need_docs_personal as $index => $personal_doc) {
                
                switch ($personal_doc) {
                    case 1:
                        $personal_title  = "Паспорт"; 
                        break;
                    case 2:
                        $personal_title  = "СНИЛС";
                        break;
                    case 3: 
                        $personal_title = "Среднее образование";
                        break;
                    case 4:
                        $personal_title = "Трудовая";
                        break; 
                    default:
                        $personal_title = "Неизвестный ид для типа личного документа";
                        break; 
                }

                switch ($personal_doc) {
                    case 1:
                        $status = $user->passports ? "matched" : "unmatched";    
                        $personalResponse[$index] = [
                            "status" => $status,
                            "document_id" => $id,
                            "personal_type" => $personal_doc,
                            "title" => $personal_title
                        ];
                        break;
                    case 2 :
                        $status = $user->snils ? "matched" : "unmatched";    
                        $personalResponse[$index] = [
                            "status" => $status,
                            "document_id" => $id,
                            "personal_type" => $personal_doc,
                            "title" => $personal_title
                        ];
                        break;
                    case 3 :
                        $status = $user->secondaryEdu ? "matched" : "unmatched";    
                        $personalResponse[$index] = [
                            "status" => $status,
                            "document_id" => $id,
                            "personal_type" => $personal_doc,
                            "title" => $personal_title
                        ];
                        break;
                    case 4 :
                        $status = $user->employmentHistory ? "matched" : "unmatched";    
                        $personalResponse[$index] = [
                            "status" => $status,
                            "document_id" => $id,
                            "personal_type" => $personal_doc,
                            "title" => $personal_title
                        ];
                        break;
                    default:
                        $personalResponse[$index] = [
                            "document_id" => $id,
                            "personal_type" => $personal_title
                        ];
                        break;
                }
            }
        }

        return collect($personalResponse) ?? [];
    }

    // при подаче заявки
    public function getResultChecked($need_docs_personal, $selectedDocuments, $id)
    {   
        $personalResponse = [];
        foreach ($need_docs_personal as $index => $personal_doc) {
            switch ($personal_doc) {
                case 1:
                    $status = (count($selectedDocuments["passport_id"]) > 0) ? true : false;    
                    
                    $personalResponse[$index] = [
                        "status" => $status,
                        "document_id" => $id
                    ];
                    break;
                case 2 :
                    $status = (count($selectedDocuments["snils_id"]) > 0) ? true : false;    
                    $personalResponse[$index] = [
                        "status" => $status,
                        "document_id" => $id
                    ];
                    break;
                case 3 :
                    $status = (count($selectedDocuments["school_id"]) > 0) ? true : false;    
                    $personalResponse[$index] = [
                        "status" => $status,
                        "document_id" => $id
                    ];
                    break;
                case 4 :
                    $status = (count($selectedDocuments["employmentHistory_id"]) > 0) ? true : false;    
                    $personalResponse[$index] = [
                        "status" => $status,
                        "document_id" => $id
                    ];
                    break;
                default:
                    $personalResponse[$index] = [
                        "document_id" => $id
                    ];
                    break;
            }
        }

        foreach ($personalResponse as $match) {   
            if($match["status"] == true) {
                return true;
            }
        }
        return false;
    }

    //  после подачи заявки в УЖЕ СФОРМИРОВАННОЙ  корректируем статус адмишена
    public function correctionStatus($user_documents, $need_docs_personal)
    {   
        // если не передают документы, например когда юр. лицо вставляет новых студентов в курс
        if($user_documents == null) {
            return false;
        }

        $statusPersonal = [];
        $statusOther = [];
        foreach ($need_docs_personal['array_documents'] as $index =>  $personal_doc) {
            switch ($personal_doc) {
                case 1:
                    $statusPersonal[$index] = (count($user_documents["passport_id"]) > 0) ? true : false;
                break;
                case 2 :
                    $statusPersonal[$index] = (count($user_documents["snils_id"]) > 0) ? true : false;    
                break;
                case 3 :
                    $statusPersonal[$index] = (count($user_documents["school_id"]) > 0) ? true : false;
                break;
                case 4 :
                    $statusPersonal[$index] = (count($user_documents["employmentHistory_id"]) > 0) ? true : false;
                break;
                default:
                    return 'Если появился новый тип документа, сообщиете об этом'; // xx
                break;
            }
        }

        // если персональный можно заменить прочим документом, также учтем
        $user_other_docs = OtherDoc::find($user_documents['other_documents']);

        foreach ($user_other_docs as $docIndex => $other_doc) {
            $statusOther[$docIndex] = in_array($other_doc->type_id, $need_docs_personal['other_type_docs']) ? true : false;
        }

        if(in_array(true, $statusPersonal)  || in_array(true, $statusOther)) {
            return true;
        }  else {
            return false;
        }
    }




}