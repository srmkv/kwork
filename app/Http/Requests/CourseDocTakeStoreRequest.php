<?php

namespace App\Http\Requests;

use App\Models\Course\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CourseDocTakeStoreRequest extends FormRequest
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
            'id' => 'integer|exists:course_docs_take,id',
            'course_id' => 'integer|exists:courses,id',
            'images' => 'array',
            'title' => 'string|max:255',
            'sub_title' => 'string|max:255',
            'description' => 'string|max:255',

        ];
    }

    public function messages()
    {
        return [
            'id.exists' => 'Нет выдаваемого документа с таким id',
            'course_id.exists' => 'Нет курса с таким id',
        ];
    }

    protected function prepareForValidation()
    {
        if(!$this->course_id){
            $this->course_id = Course::create(['admin_id' => auth()->user()->id])->id;
        }
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
