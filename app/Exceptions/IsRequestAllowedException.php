<?php

namespace App\Exceptions;

use Exception;

class IsRequestAllowedException extends Exception
{
    public function report()
    {
        // \Log::debug('example report');
    }


}
