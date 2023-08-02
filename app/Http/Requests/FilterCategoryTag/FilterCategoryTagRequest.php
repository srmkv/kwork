<?php

namespace App\Http\Requests\FilterCategoryTag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class FilterCategoryTagRequest extends FormRequest
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
            'title' =>  'required|string|min:3|max:255|unique:filter_category_tag,title',
            'type_id' =>  'integer|exists:course_category_tag_types,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Заполните название тэга',
            'title.min' => 'Минимальная длинна название тэга: :min',
            'title.unique' => 'Тэг с таким названием уже существует',
            // 'type_id.required' => 'Тип тэга должен быть заполнен',
            'type_id.exists' => 'Нет типа тэга с таким id',
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
