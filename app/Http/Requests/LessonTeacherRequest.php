<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class LessonTeacherRequest extends FormRequest
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
            'id' => 'required|exists:course_section_lessons,id',
            'teacher_id' => 'required|exists:teachers,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'id урока должен быть заполнен',
            'id.exists' => 'Нет урока с таким id',
            'teacher_id.required' => 'id преподавателя должен быть заполнен',
            'teacher_id.exists' => 'Нет преподавателя с таким id',
            
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
