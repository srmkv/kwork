<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{   

    use HasFactory;

    public $timestamps = false;
    protected $table = 'countries';

}
