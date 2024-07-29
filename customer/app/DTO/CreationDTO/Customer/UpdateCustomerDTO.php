<?php

namespace App\DTO\CreationDTO\Customer;

class UpdateCustomerDTO extends AbstractCustomerDTO
{
    public function __construct(
        private string $id
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
