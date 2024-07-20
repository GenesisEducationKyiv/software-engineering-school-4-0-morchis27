<?php

namespace App\Service\Subscription;

use App\DTO\CreationDTO\Subscriber\SubscriberDTO;
use App\DTO\VerificationDTO\Subscriber\SubscriberVerificationDTO;
use App\Enum\EmailVerificationCode;
use App\Exceptions\ModelNotSavedException;
use App\Models\NotifiableInterface;
use App\Repository\Subscriber\SubscriberRepositoryInterface;
use App\Service\Message\Messages\DailyExchangeRateNotificationMessage;
use App\Service\Message\Messages\EmailVerificationMessage;
use App\Service\MessageBroker\MessageBrokerInterface;
use Illuminate\Http\Request;

class SubscriptionService implements SubscriptionServiceInterface
{
    public function __construct(
        private SubscriberRepositoryInterface $subscriberRepository,
        private MessageBrokerInterface $messageBroker
    ) {
    }

    /**
     * @param SubscriberDTO $subscriberDTO
     * @return void
     * @throws ModelNotSavedException
     */
    public function subscribe(SubscriberDTO $subscriberDTO): void
    {
        $subscriber = $this->subscriberRepository->create($subscriberDTO);
        $this->handleVerificationNotification($subscriber);
        $this->handleDailyExchangeRateSubscription($subscriber);
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
     * @return SubscriberDTO
     */
    public function makeSubscriberDTO(Request $request): SubscriberDTO
    {
        $createSubscriberDTO = new SubscriberDTO();
        $createSubscriberDTO->fillByRequest($request);

        return $createSubscriberDTO;
    }

    public function handleVerificationNotification(NotifiableInterface $notifiable): void
    {
        $this->messageBroker
            ->publish(new EmailVerificationMessage($notifiable->getNotificationRefer()));
    }

    private function handleDailyExchangeRateSubscription(NotifiableInterface $notifiable): void
    {
        $this->messageBroker
            ->publish(new DailyExchangeRateNotificationMessage($notifiable));
    }

    public function unsubscribe(SubscriberDTO $subscriberDTO): bool
    {
        return $this->subscriberRepository->delete(
            $this->subscriberRepository->findByEmail($subscriberDTO->getEmail())->id
        );
    }
}
