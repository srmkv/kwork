<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocOtherType extends Model
{
    use HasFactory;
    
    protected $table = 'other_docs_types';
    protected $guarded = [];
    public $timestamps = false;

}
