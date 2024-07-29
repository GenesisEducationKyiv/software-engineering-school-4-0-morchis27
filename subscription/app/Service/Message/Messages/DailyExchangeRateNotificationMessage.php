<?php

namespace App\Service\Message\Messages;

use App\Models\NotifiableInterface;
use App\Service\Message\KafkaMessageWrapper;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

readonly class DailyExchangeRateNotificationMessage extends KafkaMessageWrapper
{
    public function __construct(
        private NotifiableInterface $notifiable,
    ) {
        parent::__construct();

        $this->setPayload($this->getPayloadArray());
    }

    /**
     * @return array<string, array<string, string>|Uuid|string>.
     * @throws \Exception
     */
    private function getPayloadArray(): array
    {
        return [
            'eventId' => Uuid::uuid4(),
            'eventType' => $this->getType(),
            'timestamp' => Carbon::now()->toIso8601String(),
            'data' => [
                'to' => $this->notifiable->getNotificationRefer(),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'mail.dailyExchangeRateNotification';
    }

    public function getTopic(): string
    {
        return 'daily-mail-requests';
    }
}
