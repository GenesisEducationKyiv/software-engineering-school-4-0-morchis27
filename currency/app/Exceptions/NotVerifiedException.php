<?php

namespace App\Exceptions;

use Exception;

class NotVerifiedException extends Exception
{
    public int $status = 500;
}
