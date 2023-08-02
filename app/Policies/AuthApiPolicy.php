<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthApiPolicy
{
    use HandlesAuthorization;
    
    
    public function __construct()
    {
        
    }
}
