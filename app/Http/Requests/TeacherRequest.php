<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class TeacherRequest extends FormRequest
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
            'id' => 'integer|exists:teachers,id',
            'name' => 'required|string|max:100',
            'photo' => 'file',
            'degree_id' => 'integer|exists:academic_degrees,id'
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'Нет преподавателя с таким id',
            'degree_id.exists' => 'Нет степени с таким id',
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
