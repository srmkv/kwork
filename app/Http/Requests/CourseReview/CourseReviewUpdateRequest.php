<?php

namespace App\Http\Requests\CourseReview;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CourseReviewUpdateRequest extends FormRequest
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
            'id' => 'required|integer|exists:course_reviews,id',
            'text' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Id отзыва должен быть заполнен',
            'id.exists' => 'Нет отзыва с таким id',
            'text.required' => 'Содержание отзыва должно быть заполнено',
            'text.max' => 'Размер отзыва не должен превышать :max символов',
            'rating.min' => 'Рэйтинг не может быть ниже :min',
            'rating.max' => 'Рэйтинг не может быть выше :max',
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
