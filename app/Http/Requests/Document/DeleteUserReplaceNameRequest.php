<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserReplaceNameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'replace_id' => 'exists:user_replaces_name'
        ];
    }


    public function messages()
    {
        return [
            'replace_id.exists' => 'Не существует документа',
        ];
    }


}
