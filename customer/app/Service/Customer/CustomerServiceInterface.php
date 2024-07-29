<?php

namespace App\Service\Customer;


interface CustomerServiceInterface
{
    public function create(string $email): void;

    public function deleteByEmail(string $email): bool;
}
