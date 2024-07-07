<?php

namespace App\Listeners;

use App\Events\Subscribed;
use App\Service\Subscription\SubscriptionServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleSubscribedListener implements ShouldQueue
{
    public function __construct(
        private bool $shouldBeVerified,
        private SubscriptionServiceInterface $subscriptionService
    ) {
    }

    /**
     * @param Subscribed $event
     * @return void
     */
    public function handle(Subscribed $event): void
    {
        if ($this->shouldBeVerified) {
            $this->subscriptionService->handleVerificationNotification($event->subscriber);
        }

        if (!$this->shouldBeVerified) {
            $this->subscriptionService->verify($event->subscriber);
        }
    }
}
