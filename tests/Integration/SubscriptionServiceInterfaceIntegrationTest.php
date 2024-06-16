<?php

namespace Tests\Integration;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Enum\Currency;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class SubscriptionServiceInterfaceIntegrationTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetCurrentRateIntegration()
    {
        $currencyExchangeRateRepository = $this->createMock(CurrencyExchangeRateRepositoryInterface::class);
        $currencyExchangeRateService = new CurrencyExchangeRateService($currencyExchangeRateRepository);

        $currencyFrom = Currency::USD;
        $currencyTo = Currency::UAH;

        $result = $currencyExchangeRateService->getCurrentRate($currencyFrom, $currencyTo);

        $this->assertInstanceOf(ExchangeRateDTO::class, $result);
    }
}
