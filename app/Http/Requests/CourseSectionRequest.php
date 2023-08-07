<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CourseSectionRequest extends FormRequest
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
            'id' => 'required|exists:course_sections,id',
            'teacher_id' => 'integer|exists:course_section_teachers,teacher_id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Id секции курса должен быть установлен',
            'id.exists' => 'Нет секции курса с таким id',
            'teacher_id.exists' => 'Учитель с таким id не привязан к данной секции',
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
