<?php

namespace App\Exceptions;

use Exception;

class InvalidFiltersException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message = "Invalid filters provided", $code = 400)
    {
        parent::__construct($message, $code);
    }
}
