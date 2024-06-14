<?php

namespace App\Service\Subscription;

use App\DTO\CreateSubscriberDTO;

interface SubscriptionInterface
{
    public function subscribe(CreateSubscriberDTO $subscriberDTO): void;
}
