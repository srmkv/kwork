<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PinRequest extends FormRequest
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
            'pin_id' => 'exists:pins,id',

        ];



    }

    public function messages()
    {
        return [
            'pin_id.exists' => 'Пин код не существует',
        ];
    }


}
