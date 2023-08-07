<?php
namespace App\Services\Order;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\OtherDoc;

class OrderOtherDocService
{
    
    // проверка на наличие нужного доп. документа
    public function checkOtherTypeDocument($need_docs, $user_other_docs) 
    {   

        $successArr = [];
        foreach ($user_other_docs as $docIndex => $other_doc) {
            // dd($need_docs);
            if(!empty($need_docs)) {
                if(in_array($other_doc->type_id, $need_docs) ) {
                    array_push($successArr, $other_doc->type_id);
                }
            }

        }

        $successArr = ($successArr == []) ? false : $successArr;
        return $successArr;
    }

    // проверка доп. докумнетов в адмишене типа "other"
    public function correctionStatus( $user_other_docs, $need_docs)
    {   
        if($user_other_docs == null) {
            return false;
        } else {
            $user_other_docs = OtherDoc::find($user_other_docs['other_documents']);
            $statusOther = [];
            foreach ($user_other_docs as $docIndex => $other_doc) {
                $statusOther[$docIndex] = in_array($other_doc->type_id, $need_docs) ? true : false;
            }

            return in_array(true, $statusOther);
        }

    }


}