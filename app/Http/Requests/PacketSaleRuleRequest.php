<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class PacketSaleRuleRequest extends FormRequest
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
            'id' => 'required|exists:packet_sale_rules,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'id правила продажи должен быть заполнен',
            'id.exists' => 'Нет правила продажи с таким id',
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
