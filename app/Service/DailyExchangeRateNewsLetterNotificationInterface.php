<?php

namespace App\Service;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Models\NotifiableInterface;

interface DailyExchangeRateNewsLetterNotificationInterface
{
    public function sendDailyExchangeRateNewsLetterNotification(
        NotifiableInterface $notifiable,
        ExchangeRateDTOInterface $exchangeRate
    ): void;
}
