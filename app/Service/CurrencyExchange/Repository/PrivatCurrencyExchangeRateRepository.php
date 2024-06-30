<?php

namespace App\Service\CurrencyExchange\Repository;

use App\DTO\ExchangeRateDTO\PrivatExchangeRateDTO;
use App\Enum\ConfigSpaceName;
use App\Enum\Currency;
use App\Exceptions\MalformedApiResponseException;
use App\Utils\Utilities;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Factory as Http;
use Psr\Log\LoggerInterface;

class PrivatCurrencyExchangeRateRepository implements CurrencyExchangeRateRepositoryInterface
{
    public function __construct(
        private Http $http,
        private LoggerInterface $logger,
        private Utilities $utilities
    ) {
    }

    /**
     * @throws ConnectionException
     */
    private function getExchangeResponse(string $url): Response
    {
        $response = $this->http->withOptions(['verify' => true])
            ->get($url);
        $this->logger->info('PB has a response: ', ['response' => $response]);

        return $response;
    }

    /**
     * @throws Exception
     */
    private function getExchangeRateApiUrl(): string
    {
        return $this->utilities->getStringValueFromEnvVariable(
            ConfigSpaceName::CURRENCY_REPOSITORY->value,
            'repositories.privat.exchangeServiceApiHost'
        );
    }

    /**
     * @throws ConnectionException
     * @throws MalformedApiResponseException
     * @throws Exception
     */
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): PrivatExchangeRateDTO
    {
        $url = $this->getExchangeRateApiUrl();

        $responseBodyArray = $this->getExchangeResponse($url)->json();

        if (!is_array($responseBodyArray)) {
            throw new MalformedApiResponseException('Couldn\'t convert response to an array.');
        }
        if (!in_array(Currency::USD->value, array_column($responseBodyArray, 'ccy'))) {
            throw new MalformedApiResponseException('Rates key not found in response body.');
        }

        $usdRates = array_filter($responseBodyArray, function ($rate) {
            return $rate['ccy'] === Currency::USD->value;
        });

        return new PrivatExchangeRateDTO(array_values($usdRates)[0]);
    }
}
