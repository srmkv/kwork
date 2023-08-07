<?php

namespace App\Http\Requests;

use App\Models\Course\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class BannerStoreRequest extends FormRequest
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
            'type_id' => 'integer|exists:banner_types,id',
            'color' => 'string',
            'banner' => 'file',
        ];
    }

    public function messages()
    {
        return [
            'course_id.exists' => 'Нет курса с таким id',
            'type_id.exists' => 'Нет типа баннера с таким id',
            'course_id.required' => 'ID курса должен быть указан',
            'banner.required' => 'Нет изображения',
        ];
    }
    


    /**
     * Если передан course_id то обновляем этот курс, если нет, то создаём новый пустой
     */
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
