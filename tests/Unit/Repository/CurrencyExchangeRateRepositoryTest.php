<?php

namespace Repository;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Enum\Currency;
use App\Exceptions\MalformedApiResponseException;
use App\Service\CurrencyExchange\Repository\ApiLayerCurrencyExchangeRateRepository;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use Illuminate\Http\Client\ConnectionException;
use Tests\TestCase;

class CurrencyExchangeRateRepositoryTest extends TestCase
{
    private CurrencyExchangeRateRepositoryInterface $repository;
    private Currency $currencyFrom;
    private Currency $currencyTo;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new ApiLayerCurrencyExchangeRateRepository();
        $this->currencyFrom = Currency::USD;
        $this->currencyTo = Currency::UAH;
    }

    /**
     * @throws ConnectionException
     * @throws MalformedApiResponseException
     */
    public function testGetCurrentRateReturnsExchangeRateDTO(): void
    {
        $result = $this->repository->getCurrentRate($this->currencyFrom, $this->currencyTo);

        $this->assertInstanceOf(ExchangeRateDTO::class, $result);
    }
}
