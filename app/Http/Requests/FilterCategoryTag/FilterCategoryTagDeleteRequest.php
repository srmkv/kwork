<?php

namespace App\Http\Requests\FilterCategoryTag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class FilterCategoryTagDeleteRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'Нет тэга с таким id',
        ];
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
