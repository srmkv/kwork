<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;

    public $table = 'admin_roles';
    public $timestamps = false;
    protected $guarded = [];


    public function permissions()
    {
        return $this->belongsToMany(AdminPermission::class,'admin_roles_permissions');
    }

}
