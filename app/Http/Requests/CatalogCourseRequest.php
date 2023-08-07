<?php

namespace App\Http\Requests;

use App\Models\Course\Course;
use App\Services\CatalogService;
use Illuminate\Foundation\Http\FormRequest;

class CatalogCourseRequest extends FormRequest
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
        ];
    }

    protected function prepareForValidation()
    {
        $CatalogService = new CatalogService;
        if($this->min_price && $this->max_price){
            if($arrPartKeys = $CatalogService->preparePricesForCatalog($this->min_price, $this->max_price)){
                $this->merge(['price' => $arrPartKeys]);
            }
        }

        if($this->date_min && $this->date_max){
            if($arrPartDates = $CatalogService->prepareDatesForCatalog($this->date_min, $this->date_max)){
                $this->merge(['date' => $arrPartDates]);
            }
        }

        if($this->url){
            $this->url = \Str::of($this->url)->trim('/');
        }
        $this->merge(['with_catigories' => 1]);
    }
}
