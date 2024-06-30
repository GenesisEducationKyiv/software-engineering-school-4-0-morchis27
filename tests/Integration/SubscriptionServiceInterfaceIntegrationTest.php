<?php

namespace Tests\Integration;

use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Handlers\CurrencyExchange\HandlerInterface;
use App\Handlers\CurrencyExchange\PrivatHandler;
use App\Repositories\CurrencyExchange\RepositoryCreator\CurrencyExchangeRateRepositoryCreatorInterface;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SubscriptionServiceInterfaceIntegrationTest extends TestCase
{
    /**
     * @var array<int, HandlerInterface|MockObject>
     */
    private array $handlers;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->handlers = $this->getHandlerImplementationsArray();
    }

    /**
     * @return array<int, HandlerInterface|MockObject>
     * @throws Exception
     */
    private function getHandlerImplementationsArray(): array
    {
        $currencyExchangeRateRepositoryCreator =
            $this->createMock(CurrencyExchangeRateRepositoryCreatorInterface::class);
        return [
            new PrivatHandler($currencyExchangeRateRepositoryCreator),
        ];
    }

    /**
     * @throws \Exception
     */
    public function testGetCurrentRateIntegration(): void
    {
        // @phpstan-ignore-next-line
        $currencyExchangeRateService = new CurrencyExchangeRateService($this->handlers);

        $currencyFrom = Currency::USD;
        $currencyTo = Currency::UAH;

        $result = $currencyExchangeRateService->getCurrentRate($currencyFrom, $currencyTo);

        $this->assertInstanceOf(ExchangeRateDTOInterface::class, $result);
    }
}
