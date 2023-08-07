<?php

namespace App\Http\Requests;

use App\Models\Course\Course;
use App\Models\Course\ShoppingOffer;
use App\Models\Course\WhoSuited;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'course_id' => 'integer|exists:courses,id',
            'banner_type_id' => 'integer|exists:banner_types,id',
            'course' => 'integer|exists:courses,id',
            'name' => 'string|max:100',
            'state_id' => 'integer|exists:course_states,id',
            'academic_hours' => 'integer',
            'academic_days' => 'integer',
            'utps' => 'array',
            'description' => 'string',
            'who_suited' => 'array',
            'refinement_tag_ids' => 'array',
            'categories' => 'array',
            'is_restrict_block' => 'boolean',
            'search_tag_ids' => 'array',
            'is_edu_doc_required' => 'boolean',
            'is_by_doc_req' => 'boolean',
            'is_change_surname' => 'boolean',
            'doc_edu_ids' => 'array',
            'is_doc_take' => 'boolean',
            'doc_take_title' => 'string',
            'doc_take_sub_title' => 'string',
            'doc_take_description' => 'string',
            'study_doc_id' => 'integer|exists:course_study_docs,id',
        ];
    }

    public function messages()
    {
        return [
            'course_id.exists' => 'Нет курса с таким id',
            'course_id.required' => 'A :attribute is required',
            'study_doc_id.exists' => 'Нет документа с таким id',
            'course.exists' => 'Нет курса с таким id',
            'banner_type_id.exists' => 'Нет типа баннера с таким id',

        ];
    }

    protected function prepareForValidation()
    {
        if(isset($this->course_id) && !is_numeric($this->course_id)){
            $this->offsetUnset('course_id');
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
