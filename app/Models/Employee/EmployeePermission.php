<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePermission extends Model
{
    use HasFactory;


    public $table = 'permissions_employee';
    
}
