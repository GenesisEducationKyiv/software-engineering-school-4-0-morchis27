<?php

namespace App\Service\CurrencyExchange\Repository;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Enum\Currency;
use App\Exceptions\MalformedApiResponseException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Contracts\Config\Repository as Config;
use Psr\Log\LoggerInterface;

class ApiLayerCurrencyExchangeRateRepository implements CurrencyExchangeRateRepositoryInterface
{
    private const MAIN_CURRENCY_QUERY_PARAMETER_NAME = 'base';
    private const EXCHANGING_CURRENCY_QUERY_PARAMETER_NAME = 'symbols';

    public function __construct(
        private Config $config,
        private Http $http,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws ConnectionException
     */
    private function getExchangeResponse(string $url): Response
    {
        $response = $this->http->withOptions(['verify' => true])
            ->withHeader('apikey', $this->config->get('app.exchangeServiceApiKey'))
            ->get($url);
        $this->logger->info('Api Layer has a response: ', ['response' => $response->json()]);

        return $response;
    }

    private function getExchangeRateApiUrl(Currency $currencyFrom, Currency $currencyTo): string
    {
        $baseUrl = $this->config->get('app.exchangeServiceApiHost') . '/';
        $urn = 'exchangerates_data' . '/' . 'latest';
        $currencyFromParameter = '?' . self::MAIN_CURRENCY_QUERY_PARAMETER_NAME . '=' . $currencyFrom->value . '&';
        $currencyToParameter = self::EXCHANGING_CURRENCY_QUERY_PARAMETER_NAME . '=' . $currencyTo->value;

        return $baseUrl . $urn . $currencyFromParameter . $currencyToParameter;
    }

    /**
     * @throws ConnectionException
     * @throws MalformedApiResponseException
     */
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): ExchangeRateDTO
    {
        $url = $this->getExchangeRateApiUrl($currencyFrom, $currencyTo);

        $responseBodyArray = $this->getExchangeResponse($url)->json();

        if (!is_array($responseBodyArray)) {
            throw new MalformedApiResponseException('Couldn\'t convert response to an array.');
        }
        if (!isset($responseBodyArray['rates'])) {
            throw new MalformedApiResponseException('Rates key not found in response body.');
        }

        return new ExchangeRateDTO($responseBodyArray['rates'][$currencyTo->value]);
    }
}
