<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|int',
            // 'avatar' => 'required', //TODO string???
            'lastname' => 'string',
            'name' => 'string',
            'middle_name' => 'string',
            'phone' => 'digits:11',
            'email' => 'email:rfc,dns',
            'date_birthday' => 'date',
        ];
    }

    public function messages()
    {
        return [
            'phone.digits' => 'не верно указан номер телефона',
            'password.email' => 'не верно указан email',
            'date_of_birth.date' => 'не верный формат даты',
        ];
    }
}
