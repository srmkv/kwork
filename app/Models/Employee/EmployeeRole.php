<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class EmployeeRole extends Model
{
    use HasFactory;

    public $table = 'employee_roles';
    public $timestamps = false;


    protected $guarded = [];



    public function permissions()
    {
        return $this->belongsToMany(EmployeePermission::class,'employee_roles_permissions');
    }


}
