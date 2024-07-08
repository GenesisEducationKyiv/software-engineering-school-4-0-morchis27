<?php

namespace App\Exceptions;

use Exception;

class AlreadyExistsException extends Exception
{
    public int $status = 422;
}
