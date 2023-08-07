<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FullRegistrationRequest extends FormRequest
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
            'user_id' => 'int',
            'password' => 'required|string|min:8|max:15',
            'password_confirm' => 'required|string|min:8|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'пароль меньше 8 символов',
            'password.regex' => 'в пароле не используются все необходимые символы',
        ];
    }
}
