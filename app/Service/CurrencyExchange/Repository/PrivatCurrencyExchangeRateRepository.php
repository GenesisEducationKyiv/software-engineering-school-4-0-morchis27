<?php

namespace App\Service\CurrencyExchange\Repository;

use App\DTO\ExchangeRateDTO\PrivatExchangeRateDTO;
use App\Enum\Currency;
use App\Exceptions\MalformedApiResponseException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Contracts\Config\Repository as Config;
use Psr\Log\LoggerInterface;


class PrivatCurrencyExchangeRateRepository implements CurrencyExchangeRateRepositoryInterface
{
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
        $this->logger->info('PB has a response: ', ['response' => $response->json()]);

        return $response;
    }

    private function getExchangeRateApiUrl(): string
    {
        return $this->config->get('currencyRepository.repositories.privat.exchangeServiceApiHost');
    }

    /**
     * @throws ConnectionException
     * @throws MalformedApiResponseException
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
