<?php

namespace App\Handlers\CurrencyExchange;


use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Exceptions\MalformedApiResponseException;

class AbstractHandler implements HandlerInterface
{
    private ?HandlerInterface $nextHandler = null;

    public function setNext(HandlerInterface $handler): HandlerInterface
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    /**
     * @throws MalformedApiResponseException
     */
    public function handle(Currency $currencyFrom, Currency $currencyTo): ?ExchangeRateDTOInterface
    {
        if(!$this->nextHandler) {
            throw new MalformedApiResponseException();
        }

        return $this->nextHandler->handle($currencyFrom, $currencyTo);
    }
}
