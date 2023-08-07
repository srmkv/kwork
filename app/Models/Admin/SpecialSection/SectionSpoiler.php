<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionSpoiler extends Model
{
    use HasFactory;
    
    public $table = 'admin_section_spoilers';
    public $timestamps = false;
    protected $guarded = [];

    public function formsDataOrg()
    {
        return $this->hasMany(FormDataOrg::class, 'admin_section_spoiler_id');
    }

    public function formsOrgUnit()
    {
        return $this->hasMany(FormOrgUnits::class, 'admin_section_spoiler_id');
    }

    public function formsEduProgram()
    {
        return $this->hasMany(FormEduProgram::class, 'admin_section_spoiler_id');
    }

    public function formsMaterialEquipment()
    {
        return $this->hasMany(FormMaterialEquipment::class, 'admin_section_spoiler_id');
    }

    public function formsFellowshipMeasure()
    {
        return $this->hasMany(FormFellowshipMeasure::class, 'admin_section_spoiler_id');
    }

    public function formsVacantPlaces()
    {
        return $this->hasMany(FormVacantPlace::class, 'admin_section_spoiler_id');
    }

    public function formsDataDirector()
    {
        return $this->hasMany(FormDataDirector::class, 'admin_section_spoiler_id');
    }



    public function formsDataDirectorEdu()
    {
        return $this->hasMany(FormDataDirectorEdu::class, 'admin_section_spoiler_id');
    }


    public function formsDocument()
    {
        return $this->hasMany(FormDocument::class, 'admin_section_spoiler_id');
    }

    public function formsAccesibleEnv()
    {
        return $this->hasMany(FormAccesibleEnv::class, 'admin_section_spoiler_id');
    }

    public function formsEducation()
    {
        return $this->hasMany(FormEducation::class, 'admin_section_spoiler_id');
    }

    public function formsInternationalCooperation()
    {
        return $this->hasMany(FormInternationalCooperation::class, 'admin_section_spoiler_id');
    }


    public function formsFinancialSource()
    {
        return $this->hasMany(FormFinancialSource::class, 'admin_section_spoiler_id');
    }


    public function formsEconomicActivityPlan()
    {
        return $this->hasMany(FormEconomicActivityPlan::class, 'admin_section_spoiler_id');
    }


    public function formsSpeciality()
    {
        return $this->hasMany(FormSpeciality::class, 'admin_section_spoiler_id');
    }



}
