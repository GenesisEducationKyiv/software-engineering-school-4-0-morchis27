<?php

namespace App\Service\CurrencyExchange;

use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Handlers\CurrencyExchange\ApiLayerHandler;
use App\Handlers\CurrencyExchange\CurrencyBeaconHandler;
use App\Handlers\CurrencyExchange\HandlerInterface;
use App\Handlers\CurrencyExchange\PrivatHandler;
use Exception;

class CurrencyExchangeRateService implements CurrencyExchangeRateInterface
{
    /**
     * @param array<int, HandlerInterface> $handlers
     */
    public function __construct(
        private array $handlers,
    ) {
        $this->setHandlers($this->handlers);
    }

    /**
     * @throws Exception
     */
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): ?ExchangeRateDTOInterface
    {
        return $this->handlers[0]->handle($currencyFrom, $currencyTo);
    }

    /**
     * @param array<int, HandlerInterface> $handlers
     * @return void
     */
    private function setHandlers(array $handlers): void
    {
        for ($i = 0; $i < count($handlers) - 1; $i++) {
            $handlers[$i]->setNext($handlers[$i + 1]);
        }
    }
}
