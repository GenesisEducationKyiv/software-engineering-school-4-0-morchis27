<?php

namespace App\Service\Subscription;

use App\DTO\CreationDTO\Subscriber\CreateSubscriberDTO;
use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\DTO\VerificationDTO\Subscriber\SubscriberVerificationDTO;
use App\Enum\EmailVerificationCode;
use App\Events\Subscribed;
use App\Exceptions\ModelNotSavedException;
use App\Models\NotifiableInterface;
use App\Notifications\DailyExchangeRateNotification;
use App\Notifications\VerifyEmailQueued;
use App\Repository\Subscriber\SubscriberRepositoryInterface;
use App\Utils\Utilities;
use Illuminate\Http\Request;

class SubscriptionService implements SubscriptionServiceInterface
{
    public function __construct(
        private SubscriberRepositoryInterface $subscriberRepository,
        private Utilities $utilities,
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
     * @param NotifiableInterface $subscriber
     * @return SubscriberVerificationDTO
     */
    public function verify(NotifiableInterface $subscriber): SubscriberVerificationDTO
    {
        if ($this->subscriberRepository->isVerified($subscriber)) {
            return new SubscriberVerificationDTO(
                response: true,
                responseCode: EmailVerificationCode::ALREADY_SUBSCRIBED->value
            );
        }

        $this->subscriberRepository->verify($subscriber);

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
        $notifiable->notify(new VerifyEmailQueued($this->utilities));
    }

    public function sendDailyExchangeRateNewsLetterNotification(
        NotifiableInterface $notifiable,
        ExchangeRateDTOInterface $exchangeRate
    ): void {
        $notifiable->notify(new DailyExchangeRateNotification($exchangeRate->getSellExchangeRate()));
    }
}
