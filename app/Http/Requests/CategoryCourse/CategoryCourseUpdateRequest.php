<?php

namespace App\Http\Requests\CategoryCourse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CategoryCourseUpdateRequest extends FormRequest
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
            'id' => 'required|integer|exists:course_categories,id',
            'title' =>  'required|string|max:255|unique:course_categories,title',
            'tag_id' =>  'integer|exists:filter_category_tag,id',
            'parent_id' =>  'integer|exists:course_categories,id',
            'description' =>  'string|max:255',
            'seo_title' =>  'string|max:255',
            'seo_description' =>  'string|max:255',
            'seo_keywords' =>  'string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название категории должно быть заполненно',
            'title.unique' => 'Категория с таким названием уже существует',
            'tag_id.required' => 'Тэг фильтрации должен быть установлен',
            'tag_id.exists' => 'Нет тэга фильтрации с таким id',
            'parent_id.exists' => 'Нет категории с таким id',
            'id.exists' => 'Нет категории с таким id',
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
