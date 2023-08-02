<?php

namespace App\Http\Requests\CourseReview;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CourseReviewStoreRequest extends FormRequest
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
            'text' => 'required|string|max:255',
            'parent_id' => 'integer|exists:course_reviews,id',
            'rating' => 'integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => 'Id курса должен быть заполнен',
            'course_id.exists' => 'Нет курса с таким id',
            'parent_id.exists' => 'Нет отзыва с таким id',
            'text.required' => 'Содержание отзыва должно быть заполнено',
            'text.max' => 'Размер отзыва не должен превышать :max символов',
            'rating.min' => 'Рэйтинг не может быть ниже :min',
            'rating.max' => 'Рэйтинг не может быть выше :max',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['user_id' => auth()->user()->id]);
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
