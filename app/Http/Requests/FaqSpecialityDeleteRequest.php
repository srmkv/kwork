<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class FaqSpecialityDeleteRequest extends FormRequest
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

    public function rules()
    {
        return [
            'id' => 'required|exists:category_course_speciality_faqs,id',
            'question_id' => 'exists:category_course_speciality_faq_questions,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'id вопроса должен быть заполнен',
            'id.exists' => 'Нет вопроса с таким id',
            'question_id.exists' => 'Нет вопроса с таким id',
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
