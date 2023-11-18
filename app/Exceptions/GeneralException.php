<?php

namespace App\Exceptions;

use Exception;

/**
 * Generic Exception used to return error message in the response of API.
 * Handled in Handler.php file
 */
class GeneralException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
