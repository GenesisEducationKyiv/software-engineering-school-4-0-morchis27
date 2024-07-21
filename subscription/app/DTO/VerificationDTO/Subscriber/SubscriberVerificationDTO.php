<?php

namespace App\DTO\VerificationDTO\Subscriber;

use App\DTO\VerificationDTO\BasicVerificationDTOInterface;

class SubscriberVerificationDTO implements BasicVerificationDTOInterface
{
    public function __construct(
        private bool $response,
        private int $responseCode
    ) {
    }

    /**
     * @return bool
     */
    public function getResponse(): bool
    {
        return $this->response;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @return array{response: bool, responseCode: int}
     */
    public function toArray(): array
    {
        return [
            'response' => $this->response,
            'responseCode' => $this->responseCode,
        ];
    }
}
