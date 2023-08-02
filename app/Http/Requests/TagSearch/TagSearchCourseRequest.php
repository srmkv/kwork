<?php

namespace App\Http\Requests\TagSearch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class TagSearchCourseRequest extends FormRequest
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
            'title' =>  'required|string|max:50|unique:tag_search_courses,title',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название тэга должно быть заполнено',
            'title.min' => 'Длина названия тэга не может быть менее :min символов',
            'title.max' => 'Длина названия тэга не может быть более :max символов',
            'title.unique' => 'Такой тэг уже есть',
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
