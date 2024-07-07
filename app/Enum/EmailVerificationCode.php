<?php

namespace App\Enum;

enum EmailVerificationCode: int
{
    case ALREADY_SUBSCRIBED = 1;
    case SUBSCRIBED_SUCCESS = 2;
}
