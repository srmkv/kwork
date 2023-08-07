<?php
namespace App\Services\Admin\SettingMainPage;

use App\Models\SettingMainPage\Contact;
use App\Models\SettingMainPage\HeaderPhone;

class ContactService
{
    public function updateEmail($mail)
    {   
        if(Contact::find(1) == null ) {
            $contact = new Contact;
        } else {
            $contact = Contact::find(1);
        }

        $contact->update([
            'main_contacts->mail' => $mail
        ]);
        $contact->save();

        return $contact;
    }

    public function newPhone($phoneNumber)
    {
        $phone = new HeaderPhone;
        $phone->phone = $phoneNumber ?? '';
        $phone->save();
        return $phone;
    }

    public function updatePhone($phone_id, $phoneNumber)
    {
        $phone = HeaderPhone::find($phone_id);
        $phone->phone =  $phoneNumber ?? $phone->phone;
        $phone->save();
        return $phone;
    }

    public function removePhone($phone_id)
    {
        $phone = HeaderPhone::find($phone_id);
        if ($phone == null) {
            return response()->json([
                'message' => 'Телефон не найден..',
                'code' => 404
            ],404);
        } else {
            $phone->delete();
            return response()->json([
                'message' => 'Телефон удален..',
                'code' => 200
            ],200);
        }
    }

    public function getEmail()
    {
        $contact = Contact::find(1); 
        return $contact->append('main_contacts')->main_contacts["mail"];
    }
}