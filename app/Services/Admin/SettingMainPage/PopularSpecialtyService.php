<?php
namespace App\Services\Admin\SettingMainPage;
use App\Models\Course\CategoryCourse;

use App\Models\SettingMainPage\PopularSpecialty;

class PopularSpecialtyService
{   
    public function __construct()
    {
        $this->specialties = PopularSpecialty::find(1) ?? new PopularSpecialty;
    }

    public function getAll()
    {
        $specialties = CategoryCourse::where('tag_id', 4)->get();

        $specs = $specialties->map(function ($spec) {
            return collect($spec->toArray())
                ->only(['id', 'title'])
                ->all();
        });
        return $specs;
    }

    public function sync($data)
    {   
        $this->specialties->specialties = $data['specialties'];
        $this->specialties->save();
        $categoriesIds = $this->specialties->append('specialties')->specialties;
        $specialtiesChecked = CategoryCourse::find($categoriesIds);
        $specsChecked = $specialtiesChecked->map(function ($spec) {
            return collect($spec->toArray())
                ->only(['id', 'title'])
                ->all();
        });
        return $specsChecked;
    }
}