<?php

namespace App\Http\Requests;

use App\Models\Course\Course;
use App\Models\Course\CourseState;
use App\Models\Course\ShoppingOffer;
use App\Models\Course\WhoSuited;
use App\Models\EduOrganization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CourseStoreRequest extends FormRequest
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
            'name' => 'string|nullable|max:150|unique:courses,name',
            'state_id' => 'integer|exists:course_states,id',
            'academic_hours' => 'integer|nullable',
            'academic_days' => 'integer|nullable',
            'utps' => 'array',
            'description' => 'string|nullable',
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
            'faqs' => 'array',
            'study_docs' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'course_id.exists' => 'Нет курса с таким id',
            'state_id.exists' => 'Нет статуса курса с таким id',
            'name.unique' => 'Такое название курса уже есть',
            'name.max' => 'Название курса не может быть длинее :value символов'
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
        
        if($this->name){
            $this->merge(['slug' => \Str::slug($this->name)]);
        }
        $this->merge(['edu_organization_id' => EduOrganization::first()?->id]);
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
