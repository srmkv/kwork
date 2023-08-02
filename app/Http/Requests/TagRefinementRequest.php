<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRefinementRequest extends FormRequest
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
            'title' => 'required|string|max:100'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Поле :attribute должно быть заполненно',
            'degree_id.exists' => 'Нет степени с таким id',
        ];
    }

    protected function prepareForValidation()
    {
        if($this->title){
            $this->merge(['slug' => \Str::slug($this->title)]);
        }
    }
}
