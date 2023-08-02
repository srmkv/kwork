<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormFinancialSource extends Model
{
    use HasFactory;

    public function years()
    {
        return $this->hasMany(FinancialYear::class, 'form_financial_source_id');

    }


}
