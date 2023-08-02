<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return string[]
     */
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
