<?php

namespace App\Service\CurrencyExchange;

use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Handlers\CurrencyExchange\ApiLayerHandler;
use App\Handlers\CurrencyExchange\CurrencyBeaconHandler;
use App\Handlers\CurrencyExchange\PrivatHandler;
use Exception;

class CurrencyExchangeRateService implements CurrencyExchangeRateInterface
{
    public function __construct(
        private PrivatHandler $privatHandler,
        private CurrencyBeaconHandler $currencyBeaconHandler,
        private ApiLayerHandler $apiLayerHandler
    ) {
    }

    /**
     * @throws Exception
     */
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): ExchangeRateDTOInterface
    {
        $this->privatHandler->setNext($this->currencyBeaconHandler)->setNext($this->apiLayerHandler);

        return $this->privatHandler->handle($currencyFrom, $currencyTo);
    }
}
