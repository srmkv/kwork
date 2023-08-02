<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'new_password' => ['required', 'string', 'min:8', 'confirmed:new_password_confirmation'],
            'current_password' => ['required', 'string' ]
            // 'email' => ['required', 'string', 'email', 'max:255']
        ];
    }
}
