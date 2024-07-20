<?php

namespace App\Exceptions;

use Exception;

class ModelNotSavedException extends Exception
{
    public int $status = 500;
}
