<?php

namespace App\Service\Subscription;

use App\DTO\CreateSubscriberDTO;
use App\Events\Subscribed;
use App\Models\Subscriber;
use App\Repositories\Subscriber\SubscriberRepository;
use Exception;

class SubscriptionService implements SubscriptionInterface
{
    public function __construct(
        private SubscriberRepository $subscriberRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function subscribe(CreateSubscriberDTO $subscriberDTO): void
    {
        $subscriber = $this->subscriberRepository->create($subscriberDTO);

        event(new Subscribed($subscriber));
    }
}
