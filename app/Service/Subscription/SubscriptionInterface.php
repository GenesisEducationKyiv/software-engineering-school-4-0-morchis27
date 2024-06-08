<?php

namespace App\Service\Subscription;

interface SubscriptionInterface
{
    public function subscribe(string $email): void;
}
