<?php

namespace App\Models\Admin\SpecialSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessibleEnvDoc extends Model
{
    use HasFactory;

    public $timestamps = false;


   const ACCESIBLE_ENV_IMAGES = 'media.path_accesible_env';
}
