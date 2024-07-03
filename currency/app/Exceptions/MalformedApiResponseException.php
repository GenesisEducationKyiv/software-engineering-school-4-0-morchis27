<?php

namespace App\Exceptions;

use Exception;

class MalformedApiResponseException extends Exception
{
    public int $status = 400;
}
