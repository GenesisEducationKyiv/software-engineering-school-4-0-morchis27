<?php

namespace App\Service;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Models\NotifiableInterface;

interface DailyExchangeRateNewsLetterNotificationInterface
{
    public function sendDailyExchangeRateNewsLetterNotification(
        NotifiableInterface $notifiable,
        ExchangeRateDTO $exchangeRate
    ): void;
}
