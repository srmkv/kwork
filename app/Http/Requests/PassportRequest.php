<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use Spatie\MediaLibraryPro\Rules\Concerns\ValidatesMedia;

class PassportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    // use ValidatesMedia;

    public function authorize()
    {
        return true;
    }



    public function rules()
    {
        return [
            'country_id' => 'nullable|int|max:3',
            'first_name' => 'nullable|string|min:2|max:30',
            'last_name' => 'nullable|string|min:2|max:30',
            'middle_name' => 'nullable|string|min:2|max:30',
            // 'date_of_birth' => 'nullable|date',
            
            // 'passport__1' => 'required|mimes:jpg,jpeg,png|max:10000',
            'serial_number' => 'nullable|digits:10',
            'issued_by_whom' => 'nullable|max:255',
            // 'date_issue' => 'nullable|date',
            'subdivision_code' => 'nullable|string|max:7', //add -


        ];
    }

    public function messages()
    {
        return [
            // 'pin.digits' => 'не верно указан pin-код',


        ];
    }


}
