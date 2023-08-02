<?php

namespace App\Http\Requests\TagSearch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class TagSearchCourseDeleteRequest extends FormRequest
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
            'id' =>  'required|integer|exists:tag_search_courses,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Id тэга должно быть заполнен',
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
