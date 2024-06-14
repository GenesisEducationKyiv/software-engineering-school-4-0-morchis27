<?php

namespace App\DTO;

class UpdateSubscriberDTO extends AbstractSubscriberDTO
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
