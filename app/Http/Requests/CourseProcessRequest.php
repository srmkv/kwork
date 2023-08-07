<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CourseProcessRequest extends FormRequest
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
            'course_id' => 'required|integer|exists:courses,id',
            'user_id' => 'required|integer|exists:users,id',
            'type_id' => 'required|integer|exists:course_process_types,id',
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => 'Id курса должен быть заполнен',
            'course_id.exists' => 'Нет курса с таким id',
            'user_id.required' => 'Id пользователя должен быть заполнен',
            'user_id.exists' => 'Нет пользователя с таким id',
            'type_id.required' => 'Id типа прохождения должен быть заполнен',
            'type_id.exists' => 'Нет типа прохождения с таким id',
            
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
