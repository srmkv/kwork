<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SnilsRequest extends FormRequest
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


    public function rules()
    {
        return [
            'snils_number' => 'digits:11|exclude_with:snils',
            // 'snils'=>         'exclude_with:snils_image'
            'snils_image' => 'mimes:jpg,jpeg,png|max:10000|exclude_with:snils',
        ];
    }
}
