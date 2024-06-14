<?php

namespace App\Service\Subscription;

use App\DTO\CreationDTO\Subscriber\CreateSubscriberDTO;
use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\DTO\VerificationDTO\Subscriber\SubscriberVerificationDTO;
use App\Enum\EmailVerificationCode;
use App\Events\Subscribed;
use App\Exceptions\ModelNotSavedException;
use App\Exceptions\NotVerifiedException;
use App\Models\NotifiableInterface;
use App\Notifications\DailyExchangeRateNotification;
use App\Notifications\VerifyEmailQueued;
use App\Repositories\Subscriber\SubscriberRepositoryInterface;
use Illuminate\Http\Request;

class SubscriptionService implements SubscriptionServiceInterface
{
    public function __construct(
        private SubscriberRepositoryInterface $subscriberRepository,
    ) {
    }

    /**
     * @param CreateSubscriberDTO $subscriberDTO
     * @return void
     * @throws ModelNotSavedException
     */
    public function subscribe(CreateSubscriberDTO $subscriberDTO): void
    {
        $subscriber = $this->subscriberRepository->create($subscriberDTO);

        event(new Subscribed($subscriber));
    }

    /**
     * @return SubscriberVerificationDTO
     * @throws NotVerifiedException
     */
    public function verify(): SubscriberVerificationDTO
    {
        if ($this->subscriberRepository->isVerified()) {
            return new SubscriberVerificationDTO(
                response: true,
                responseCode: EmailVerificationCode::ALREADY_SUBSCRIBED->value
            );
        }

        $this->subscriberRepository->verify();

        return new SubscriberVerificationDTO(
            response: true,
            responseCode: EmailVerificationCode::SUBSCRIBED_SUCCESS->value
        );
    }

    /**
     * @param Request $request
     * @return CreateSubscriberDTO
     */
    public function makeCreationDTO(Request $request): CreateSubscriberDTO
    {
        $createSubscriberDTO = new CreateSubscriberDTO();
        $createSubscriberDTO->fillByRequest($request);

        return $createSubscriberDTO;
    }

    public function handleVerificationNotification(NotifiableInterface $notifiable): void
    {
        $notifiable->notify(new VerifyEmailQueued());
    }

    public function sendDailyExchangeRateNewsLetterNotification(
        NotifiableInterface $notifiable,
        ExchangeRateDTO $exchangeRate
    ): void {
        $notifiable->notify(new DailyExchangeRateNotification($exchangeRate->getExchangeRate()));
    }
}
