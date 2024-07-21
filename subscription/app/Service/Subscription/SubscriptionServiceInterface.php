<?php

namespace App\Service\Subscription;

use App\DTO\CreationDTO\Subscriber\SubscriberDTO;
use App\Service\VerifiableInterface;
use Illuminate\Http\Request;

interface SubscriptionServiceInterface extends VerifiableInterface
{
    public function subscribe(SubscriberDTO $subscriberDTO): void;
    public function unsubscribe(SubscriberDTO $subscriberDTO): bool;
    public function makeSubscriberDTO(Request $request): SubscriberDTO;
}
