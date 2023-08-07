<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CourseVideoStoreRequest extends FormRequest
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
            'course_id' => 'required|integer|exists:courses,id',
            'file'  => 'required|mimes:mp4,mov,ogg,qt|max:20000'
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => 'ID курса должен быть заполнен',
            'course_id.exists' => 'Нет курса с таким id',
            'file.required' => 'Загрузите видео файл',
            'file.mimes' => 'Видео файл не корректного формата',
            'file.max' => 'Видео файл слишком большой',
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
