<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class EduOrganizationRequest extends FormRequest
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
            'id' => 'required|integer|exists:edu_organizations,id',
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'Нет организации с таким id',
            'id.required' => 'ID организации должен быть заполнен',
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
