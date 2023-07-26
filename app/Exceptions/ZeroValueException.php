<?php

namespace App\Exceptions;

use Exception;

class ZeroValueException extends Exception
{
    protected $message = 'Zero value is not allowed.';
    protected $code = 422;
}