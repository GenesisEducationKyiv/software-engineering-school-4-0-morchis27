<?php

namespace Service;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Enum\Currency;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CurrencyExchangeRateServiceTest extends TestCase
{
    private CurrencyExchangeRateRepositoryInterface $repository;
    private Currency $currencyFrom;
    private Currency $currencyTo;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getCurrencyExchangeRateRepositoryImplementation();
        $this->currencyFrom = Currency::USD;
        $this->currencyTo = Currency::UAH;
    }

    //phpcs:ignoreFile
    private function getCurrencyExchangeRateRepositoryImplementation(): CurrencyExchangeRateRepositoryInterface|MockObject
    {
        $currencyExchangeRateRepository =
            $this->createMock(CurrencyExchangeRateRepositoryInterface::class);

        return $currencyExchangeRateRepository;
    }

    public function testGetCurrentRateReturnsExchangeRate(): void
    {
        $currencyExchangeRateService = new CurrencyExchangeRateService($this->repository);

        $exchangeRateDTO = new ExchangeRateDTO(1);

        $this->repository->expects($this->once())
            ->method('getCurrentRate')
            ->with($this->equalTo($this->currencyFrom), $this->equalTo($this->currencyTo))
            ->willReturn($exchangeRateDTO);

        $result = $currencyExchangeRateService->getCurrentRate($this->currencyFrom, $this->currencyTo);

        $this->assertInstanceOf(ExchangeRateDTO::class, $result);
    }
}
