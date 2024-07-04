<?php

namespace App\Service\Currency;

use App\DTO\VerificationDTO\BasicVerificationDTOInterface;
use App\Models\NotifiableInterface;
use App\Service\MessageBroker\MessageBrokerInterface;

interface CurrencyServiceInterface
{
    public function getRate(): float;
}
