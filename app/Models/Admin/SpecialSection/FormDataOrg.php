<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormDataOrg extends Model
{
    use HasFactory;


    public function emails()
    {
        return $this->hasMany(DataOrgEmail::class);
    }

    public function phones()
    {
        return $this->hasMany(DataOrgPhone::class);
    }

}
