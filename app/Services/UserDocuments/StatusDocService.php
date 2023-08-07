<?php
namespace App\Services\UserDocuments;

class StatusDocService
{   
    // юзер может редактировать / удалять
    const DOC_STATUS_DEFAULT = 'edit_user';
    // юзер может удалять, в базе остается 
    const DOC_STATUS_RESERVE = 'reserved';
    // никто не может изменить / удалить
    const DOC_STATUS_IMMUTABLE = 'immutable';

    //  ['edit_user', 'reserved'] - юзер может удалить, но док в заявке остается
    //  ['edit_user'] - юзер может удалить , док не в заявке, полное удаление
    //  ['reserved'] - док удален у юзера, в заявке остается
    //  ['reserved', ' immutable '] // док в заявке, неизменный навсегда для всех,


    public function changeStatusReserved($documents)
    {   
        foreach ($documents as $document) {
            $status = $document->append('status_doc')->status_doc;
            array_push($status, 'reserved');
            $document->status_doc = collect($status)->unique()->values();
            $document->save();
        }
    }


    public function checkReservedDocument($document)
    {
        $status = $document->append('status_doc')->status_doc;
        if(in_array($this::DOC_STATUS_RESERVE, $status)){
            return true;
        } else {
            return false;
        }
    }

    public function deleteReservedDocument($document)
    {
        $status = $document->append('status_doc')->status_doc;
        $statusArr = collect($status)->reject(function ($value, $key) {
            return $value == $this::DOC_STATUS_DEFAULT;
        });
        $document->status_doc = $statusArr->values();
        $document->save();
    }
}