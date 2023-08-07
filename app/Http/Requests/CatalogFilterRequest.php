<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogFilterRequest extends FormRequest
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
            'url' => 'string'
        ];
    }

    protected function prepareForValidation()
    {
        if($this->url){
            $this->url = \Str::of($this->url)->trim('/');
        }
        $this->merge(['with_catigories' => 1]);
    }
}
