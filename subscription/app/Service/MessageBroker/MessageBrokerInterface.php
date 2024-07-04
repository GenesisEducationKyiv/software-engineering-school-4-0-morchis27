<?php

namespace App\Service\MessageBroker;

use App\DTO\VerificationDTO\BasicVerificationDTOInterface;
use App\Models\NotifiableInterface;
use App\Service\Message\MessageWrapperInterface;

interface MessageBrokerInterface
{
    public function publish(MessageWrapperInterface $messageWrapper): void;
}
