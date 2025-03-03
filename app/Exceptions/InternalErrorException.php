<?php

namespace App\Exceptions;

use Exception;

class InternalErrorException extends Exception
{
    public function __construct($message = "Error interno del servidor", $code = 500)
    {
        parent::__construct($message, $code);
    }
}
