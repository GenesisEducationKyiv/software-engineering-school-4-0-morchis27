<?php

namespace App\Service\Message\Messages;

use App\Enum\ConfigSpaceName;
use App\Models\NotifiableInterface;
use App\Notifications\VerifyEmailQueued;
use App\Service\Message\KafkaMessageWrapper;
use App\Utils\Utilities;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

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
            'from' => $this->utilities->getStringValueFromEnvVariable(
                ConfigSpaceName::APP->value,
                'mailFromAddress'
            ),
            'to' => $this->notifiable->getNotificationRefer(),
            'html' => $mailMessageString
        ];
    }
}
