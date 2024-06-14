<?php

namespace App\DTO;

abstract class AbstractSubscriberDTO
{
    protected string $email;

    public function getEmail(): string
    {
        return $this->email;
    }
}
