<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public int $status = 404;
}
