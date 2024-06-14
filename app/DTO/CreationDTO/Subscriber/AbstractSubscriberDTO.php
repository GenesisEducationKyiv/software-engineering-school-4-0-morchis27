<?php

namespace App\DTO\CreationDTO\Subscriber;

abstract class AbstractSubscriberDTO
{
    protected string $email;

    public function getEmail(): string
    {
        return $this->email;
    }
}
