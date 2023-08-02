<?php

namespace App\Http\Controllers\Callback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class smsRu extends Controller
{
    public function postSmsRu()
    {
        $api_id = env('API_KEY_SMSRU'); 

        /* Защита от злоумышленников - проверка принятых данных на валидность (мы расчитываем md5 хэш вашего ключа и данных на нашей стороне, чтобы его можно было проверить на вашей стороне) */
        $hash = "";
        foreach ($_POST["data"] as $entry) {
            $hash .= $entry;
        }
        if ($_POST["hash"] == hash("sha256",$api_id.$hash)) {
            // переданные данные верны
        }

        /* Обработка переданных данных */

        foreach ($_POST["data"] as $entry) {
            $lines = explode("\n",$entry);
            switch ($lines[0]) {
                case "sms_status":
                    $sms_id = $lines[1];
                    $sms_status = $lines[2];
                    $unix_timestamp = $lines[3];

                    // "Изменение статуса. Сообщение: $sms_id. Новый статус: $sms_status. Время: $unix_timestamp";
                    // Здесь вы можете уже выполнять любые действия над этими данными.
                    break;
                case "callcheck_status":
                    $check_id = $lines[1];
                    $check_status = $lines[2];
                    $unix_timestamp = $lines[3];

                    if ($check_status == "401") {
                        // Авторизация пройдена успешно. Мы получили звонок с номера, который вы нам передавали.
                        // Идентификатор авторизации $check_id (вы должны были сохранить его в вашей базе)
                    } elseif ($check_status == "402") {
                        // Истекло время, отведенное под авторизацию. Мы не получили звонка с номера, который вы нам передавали
                        // Идентификатор авторизации $check_id (вы должны были сохранить его в вашей базе)
                    }
                    break;
            }
        }
        echo "100"; /* Важно наличие этого блока, иначе наша система посчитает, что в вашем обработчике сбой */

        
    }
}
