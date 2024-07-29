<?php

namespace App\DTO\CreationDTO\Customer;

abstract class AbstractCustomerDTO
{
    protected string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
