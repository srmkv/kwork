<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class UserAvatarRequest extends FormRequest
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
            'file' => 'required|mimes:jpeg,jpg,png|max:4024'
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
    


    /**
     * Если передан course_id то обновляем этот курс, если нет, то создаём новый пустой
     */
    protected function prepareForValidation()
    {
        
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
