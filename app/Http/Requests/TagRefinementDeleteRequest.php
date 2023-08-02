<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRefinementDeleteRequest extends FormRequest
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
            'id' => 'required|integer|exists:tag_refinements,id'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Поле :attribute должно быть заполненно',
            'id.exists' => 'Нет тэга с таким id',
        ];
    }

    protected function prepareForValidation()
    {
        if($this->title){
            $this->merge(['slug' => \Str::slug($this->title)]);
        }
    }
}
