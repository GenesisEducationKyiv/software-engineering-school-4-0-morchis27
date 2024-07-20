<?php

namespace App\Service\Subscription;

use App\DTO\CreationDTO\Subscriber\CreateSubscriberDTO;
use App\Service\VerifiableInterface;
use Illuminate\Http\Request;

interface SubscriptionServiceInterface extends VerifiableInterface
{
    public function subscribe(CreateSubscriberDTO $subscriberDTO): void;
    public function makeCreationDTO(Request $request): CreateSubscriberDTO;
}
