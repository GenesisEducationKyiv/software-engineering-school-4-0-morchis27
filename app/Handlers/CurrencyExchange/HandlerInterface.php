<?php

namespace App\Handlers\CurrencyExchange;


use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;

interface HandlerInterface
{
    public function setNext(HandlerInterface $handler): HandlerInterface;

    public function handle(Currency $currencyFrom, Currency $currencyTo): ?ExchangeRateDTOInterface;
}
