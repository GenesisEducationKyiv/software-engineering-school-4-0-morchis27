<?php

namespace App\Service\Message;

use App\DTO\VerificationDTO\BasicVerificationDTOInterface;
use App\Models\NotifiableInterface;

interface MessageWrapperInterface
{
    /**
     * @param array<mixed, mixed> $payload
     * @return self
     */
    public function setPayload(array $payload): self;

    /**
     * @param array<string, string> $headers
     * @return self
     */
    public function setHeaders(array $headers): self;

    // @phpstan-ignore-next-line
    public function getMessage();

    /**
     * @return string
     */
    public function getTopic(): string;
}
