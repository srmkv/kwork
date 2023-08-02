<?php

namespace App\Http\Controllers\Api\Admin\SettingMainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\SettingMainPage\ContactService;
use App\Models\SettingMainPage\HeaderPhone;

class ContactController extends Controller
{   
    public function __construct(ContactService $contact)
    {
        $this->contact = $contact;
    }

    public function editMail(Request $request)
    {
        return $this->contact->updateEmail($request->email);
    }

    public function createPhone(Request $request)
    {
        return $this->contact->newPhone($request->phone);
    }

    public function editPhone(Request $request, $phone_id)
    {
        return $this->contact->updatePhone($phone_id, $request->phone);
    }

    public function deletePhone(Request $request, $phone_id)
    {
        return $this->contact->removePhone($phone_id);
    }

    public function allPhones(Request $request)
    {
        return HeaderPhone::all();
    }

    public function getEmail(Request $request)
    {
        return $this->contact->getEmail();
    }
}
