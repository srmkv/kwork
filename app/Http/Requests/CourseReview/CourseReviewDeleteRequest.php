<?php

namespace App\Http\Requests\CourseReview;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CourseReviewDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:course_reviews,id',
            'publish' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Id отзыва должен быть заполнен',
            'id.exists' => 'Нет отзыва с таким id',
            'publish.boolean' => 'Не верный формат метки',
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
