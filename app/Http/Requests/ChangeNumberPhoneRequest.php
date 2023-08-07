<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeNumberPhoneRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'phone' => 'required|digits:11',
        ];
    }

    public function messages()
    {
        return [
            'phone.digits' => 'не верно указан номер телефона',
        ];
    }
}
