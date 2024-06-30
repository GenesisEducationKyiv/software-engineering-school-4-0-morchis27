<?php

namespace Service;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Handlers\CurrencyExchange\ApiLayerHandler;
use App\Handlers\CurrencyExchange\CurrencyBeaconHandler;
use App\Handlers\CurrencyExchange\HandlerInterface;
use App\Handlers\CurrencyExchange\PrivatHandler;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CurrencyExchangeRateServiceTest extends TestCase
{
    //phpcs:ignoreFile
    private Currency $currencyFrom;
    private Currency $currencyTo;

    /**
     * @var array<int, HandlerInterface|MockObject>
     */
    private array $handlers = [];

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->currencyFrom = Currency::USD;
        $this->currencyTo = Currency::UAH;
        $this->handlers = $this->getHandlerImplementationsArray();
    }

    /**
     * @return array<int, HandlerInterface|MockObject>
     * @throws Exception
     */
    private function getHandlerImplementationsArray(): array
    {
        return [
            $this->createMock(HandlerInterface::class),
        ];
    }

    public function testGetCurrentRateReturnsExchangeRate(): void
    {
        // @phpstan-ignore-next-line
        $currencyExchangeRateService = new CurrencyExchangeRateService($this->handlers);

        $exchangeRateDTO = new ExchangeRateDTO(['sale' => 1.0, 'buy' => 2.0]);

        // @phpstan-ignore-next-line
        $this->handlers[0]->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($this->currencyFrom), $this->equalTo($this->currencyTo))
            ->willReturn($exchangeRateDTO);

        $result = $currencyExchangeRateService->getCurrentRate($this->currencyFrom, $this->currencyTo);

        $this->assertInstanceOf(ExchangeRateDTOInterface::class, $result);
    }
}
