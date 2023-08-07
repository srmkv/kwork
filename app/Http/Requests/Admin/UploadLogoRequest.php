<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadLogoRequest extends FormRequest
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

    public function __construct()
    {
        $this->types = [
            'size-960',
            'size-320',
            'size-1920'
        ];
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {   
        return [
            'file' => 'required|mimes:jpeg,jpg,png|max:8024',
            'type' => 'required|in:' .  implode(',', $this->types)
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Выберите файл',
            'file.mimes' => 'Не верный формат файла',
            'file.max' => 'Файл слишком большой',
        ];
    }
}



