<?php

namespace App\Service\Subscription;

use App\Events\Subscribed;
use App\Models\Subscriber;
use App\Service\CurrencyExchange\CurrencyExchangeRateInterface;
use Exception;

class SubscriptionService implements SubscriptionInterface
{
    /**
     * @throws Exception
     */
    public function subscribe(string $email): void
    {
        try {
            $subscriber = Subscriber::create([
                'email' => $email
            ]);
        } catch (Exception $e) {
            throw new Exception();
        }

        event(new Subscribed($subscriber));
    }
}
