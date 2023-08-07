<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class AddPreviewRequest extends FormRequest
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
            'preview' => 'required|file',
            'course_id' => 'required|integer|exists:courses,id'

        ];
    }

    public function messages()
    {
        return [
            'preview.required' => 'Поле :Attribute должно быть заполнено',
            'course_id.required' => 'Поле :Attribute должно быть заполнено',
            'course_id.exists' => 'Нет курса с таким id',
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
