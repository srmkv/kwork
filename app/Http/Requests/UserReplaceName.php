<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserReplaceName extends FormRequest
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
            'chanage_fio_image' => 'required|mimes:jpg,jpeg,png|max:10000',
            
            'old_name' => 'filled',
            'old_middle_name' => 'filled',
            'old_last_name' => 'filled',
            'new_name' => 'filled',
            'new_middle_name' => 'filled',
            'new_last_name' => 'filled',
        ];
    }
}
