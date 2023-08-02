<?php

namespace App\Http\Requests\CategoryCourse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CategoryCourseDeleteImageRequest extends FormRequest
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
            'id' => 'required|exists:course_categories,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'ID категории должен быть указан',
            'id.exists' => 'Нет категории с таким ID',
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
