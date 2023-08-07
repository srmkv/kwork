<?php

namespace App\Http\Requests\CategoryCourse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CategoryCourseAddTagRequest extends FormRequest
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
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:course_categories,id',
            'tag_id' => 'required|integer|exists:filter_category_tag,id',
        ];
    }

    public function messages()
    {
        return [
            'category_ids.required' => 'Укажите хотя бы одну категорию',
            'category_ids.*.exists' => 'Нет категории с таким id::input',
            'tag_id.exists' => 'Нет тэга с таким id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['slug' => \Str::slug($this->title)]);
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
