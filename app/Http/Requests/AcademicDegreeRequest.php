<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class AcademicDegreeRequest extends FormRequest
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
            'title' => 'required|string|max:100'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Поле :Attribute должно быть заполнено',
            'title.max' => 'Поле :attribute не должно превышать :max символов',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
                    'code' => 500,
                    'message' => $validator->errors()->first()
                ], 500);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
