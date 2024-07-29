<?php

namespace App\Repository\CurrencyExchange;

use App\DTO\ExchangeRateDTO\CurrencyBeaconExchangeRateDTO;
use App\Enum\Currency;
use App\Exceptions\MalformedApiResponseException;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\Response;
use Psr\Log\LoggerInterface;

class CurrencyBeaconCurrencyExchangeRateRepository implements CurrencyExchangeRateRepositoryInterface
{
    private const LATEST = 'latest';

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
            ->get($url);
        $this->logger->info('CurrencyExchange Beacon has a response: ', ['response' => $response->json()]);

        return $response;
    }

    private function getExchangeRateApiUrl(Currency $currencyFrom): string
    {
        $baseUrl = $this->config->get('currencyRepository.repositories.currencyBeacon.exchangeServiceApiHost') . '/';
        $apiKey = $this->config->get('currencyRepository.repositories.currencyBeacon.exchangeServiceApiKey') . '/';

        return $baseUrl . $apiKey . self::LATEST . '/' . $currencyFrom->value;
    }

    /**
     * @throws ConnectionException
     * @throws MalformedApiResponseException
     */
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): CurrencyBeaconExchangeRateDTO
    {
        $url = $this->getExchangeRateApiUrl($currencyFrom);

        $responseBodyArray = $this->getExchangeResponse($url)->json();

        if (!is_array($responseBodyArray)) {
            throw new MalformedApiResponseException();
        }

        if (!($responseBodyArray['result'] === 'success')) {
            throw new MalformedApiResponseException('Couldn\'t get appropriate response');
        }
        if (!isset($responseBodyArray['conversion_rates'])) {
            throw new MalformedApiResponseException('conversion_rates not found in response body.');
        }

        return new CurrencyBeaconExchangeRateDTO($responseBodyArray['conversion_rates']);
    }
}
