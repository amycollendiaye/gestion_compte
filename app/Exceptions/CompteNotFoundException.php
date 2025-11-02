<?php

namespace App\Exceptions;

use Exception;

class CompteNotFoundException extends Exception
{
     protected $message;
    protected $code;

    public function __construct($message = "Compte non trouvé", $code = 404)
    {
        parent::__construct($message, $code);
    }
}
