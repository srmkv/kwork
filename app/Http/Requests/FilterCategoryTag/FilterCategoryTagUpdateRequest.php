<?php

namespace App\Http\Requests\FilterCategoryTag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class FilterCategoryTagUpdateRequest extends FormRequest
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
            'id' =>  'required|integer|exists:filter_category_tag,id',
            'title' =>  'required|string|min:3|max:255|unique:filter_category_tag,title',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Заполните id тэга',
            'id.exists' => 'Нет тэга с таким id',
            'title.required' => 'Заполните название тэга',
            'title.min' => 'Минимальная длинна название тэга: :min',
            'title.unique' => 'Тэг с таким названием уже существует',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['slug' => \Str::slug($this->name)]);
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
