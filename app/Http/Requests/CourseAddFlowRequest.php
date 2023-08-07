<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CourseAddFlowRequest extends FormRequest
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
            'study_form_id' => 'required|int|exists:study_forms,id',
            'course_id' => 'required|int|exists:courses,id',
        ];
    }

    public function messages()
    {
        return [
            'study_form_id.required' => 'Форма обучения должна быть заполненна',
            'study_form_id.exists' => 'Нет формы обучения с таким id',
            'course_id.required' => 'Id курса должен быть установлен',
            'course_id.exists' => 'Нет курса с таким id',
        ];
    }
    
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
            'code' => 404,
            'message' => $validator->errors()->first()
        ], 404);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
