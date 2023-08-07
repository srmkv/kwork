<?php
namespace App\Services\UserDocuments;
use Illuminate\Support\Facades\Storage;
use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileSelfEmployed;
use Carbon\Carbon;
class InnService
{   
    const USER_LEGAL = 'LEGAL';
    const USER_IP = 'INDIVIDUAL';

    public static function getDataByInn($inn, $type)
    {   
        $token = config('dadata.token');
        $dadata = new \Dadata\DadataClient($token, null);
        $result = $dadata->findById("party", $inn, 1 ,[
            'type' => $type
        ]);
        if((count($result) > 0) && $type == 'LEGAL') {
            $profile = new ProfileBuiseness;
            $profile->kpp = $result[0]['data']['kpp'];
            $profile->inn = $result[0]['data']['inn'];
            $profile->ogrn = $result[0]['data']['ogrn'];
            $profile->buiseness_address = $result[0]['data']['address']['value'];
            $profile->full_title = $result[0]['data']['name']['full_with_opf'];
            $profile->short_title = $result[0]['data']['name']['short_with_opf'];
            $profile->management_position = $result[0]['data']['management']['post'];
            $profile->phone_company = $result[0]['data']['phones'];
            $profile->mail_company = $result[0]['data']['emails'];
            $profile->mailing_address = $result[0]['data']['address']['unrestricted_value'];
            $profile->index = $result[0]['data']['address']['data']['postal_code'];
            return $profile;
        } elseif ($type == 'INDIVIDUAL' && count($result) > 0) {
            $profile = new ProfileSelfEmployed;
            $profile->inn = $result[0]['data']['inn'];
            $profile->ogrnip = $result[0]['data']['ogrn'];
            $profile->full_title = $result[0]['value'];
            $profile->date_registration = Carbon::parse($result[0]['data']['ogrn_date']/1000);
            return $profile;
        } else {

            return response()->json([
                'message' => 'Не найдена организация с таким инн, либо не верно выбран тип',
                'code' => 404
            ], 404);
        }

    }

}
