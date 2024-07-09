<?php

namespace App\Service\Message\Messages;

use App\Enum\ConfigSpaceName;
use App\Models\NotifiableInterface;
use App\Service\Message\KafkaMessageWrapper;
use App\Utils\Utilities;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Ramsey\Uuid\Uuid;

readonly class DailyExchangeRateNotificationMessage extends KafkaMessageWrapper
{
    public function __construct(
        private NotifiableInterface $notifiable,
        private float $exchangeRate,
        private Utilities $utilities
    ) {
        parent::__construct();

        $this->setPayload($this->getPayloadArray());
    }

    /**
     * @return array<string, string>
     * @throws \Exception
     */
    private function getPayloadArray(): array
    {
        $mailMessageString = (new MailMessage())
            ->greeting("Hello, {$this->notifiable->getNotificationRefer()}")
            ->line("This is the exchange rate for USD to UAH {$this->exchangeRate}.")
            ->line('Thank you for subscribing!')->render()->toHtml();

        return [
            'eventId' => Uuid::uuid4(),
            'eventType' => $this->getType(),
            'timestamp' => Carbon::now()->toIso8601String(),
            'data' => [
                'from' => $this->utilities->getStringValueFromEnvVariable(
                    ConfigSpaceName::APP->value,
                    'mailFromAddress'
                ),
                'to' => $this->notifiable->getNotificationRefer(),
                'html' => $mailMessageString,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'mail.dailyExchangeRateNotification';
    }
}
