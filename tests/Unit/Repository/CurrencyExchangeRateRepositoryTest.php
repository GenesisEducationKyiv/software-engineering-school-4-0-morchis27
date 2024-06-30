<?php

namespace Repository;

use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Exceptions\MalformedApiResponseException;
use App\Repositories\CurrencyExchange\CurrencyExchangeRateRepositoryInterface;
use App\Repositories\CurrencyExchange\PrivatCurrencyExchangeRateRepository;
use App\Utils\Utilities;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class CurrencyExchangeRateRepositoryTest extends TestCase
{
    private CurrencyExchangeRateRepositoryInterface $repository;
    private Currency $currencyFrom;
    private Currency $currencyTo;

    /**
     * @var Http|MockInterface
     */
    private Http|MockInterface $http;
    /**
     * @var LoggerInterface|MockObject
     */
    private LoggerInterface|MockObject $logger;
    /**
     * @var Utilities|MockObject
     */
    private Utilities|MockObject $utilities;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->http = \Mockery::mock(Http::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->utilities = $this->createMock(Utilities::class);
        $this->repository = new PrivatCurrencyExchangeRateRepository(
        // @phpstan-ignore-next-line
            $this->http,
            $this->logger,
            $this->utilities
        );
        $this->currencyFrom = Currency::USD;
        $this->currencyTo = Currency::UAH;
    }

    /**
     * @throws ConnectionException
     * @throws MalformedApiResponseException
     */
    public function testGetCurrentRateReturnsExchangeRateDTO(): void
    {
        // @phpstan-ignore-next-line
        $guzzleResponse = new \GuzzleHttp\Psr7\Response(200, [], json_encode([
            [
                "ccy" => "EUR",
                "base_ccy" => "UAH",
                "buy" => "43.00000",
                "sale" => "44.00000",
            ],
            [
                "ccy" => "USD",
                "base_ccy" => "UAH",
                "buy" => "40.25000",
                "sale" => "40.85000",
            ]
        ]));
        $response = new Response($guzzleResponse);

        // @phpstan-ignore-next-line
        $this->http->shouldReceive('withOptions')
            ->once()
            ->andReturnSelf();

        // @phpstan-ignore-next-line
        $this->http->shouldReceive('get')
            ->once()
            ->andReturn($response);

        // @phpstan-ignore-next-line
        $this->utilities->expects($this->once())
            ->method('getStringValueFromEnvVariable')
            ->willReturn(Config::get('currencyRepository.repositories.privat.exchangeServiceApiHost'));

        $result = $this->repository->getCurrentRate($this->currencyFrom, $this->currencyTo);

        $this->assertInstanceOf(ExchangeRateDTOInterface::class, $result);
    }
}
