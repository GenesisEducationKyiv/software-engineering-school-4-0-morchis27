<?php

namespace App\Exceptions;

use Exception;

class ModelNotExistsException extends Exception
{
    public int $status = 500;
}
