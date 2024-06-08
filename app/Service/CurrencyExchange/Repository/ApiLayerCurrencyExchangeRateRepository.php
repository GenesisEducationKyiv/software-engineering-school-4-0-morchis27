<?php

namespace App\Service\CurrencyExchange\Repository;

use App\Enum\Currencies;
use App\Exceptions\MalformedApiResponseException;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ApiLayerCurrencyExchangeRateRepository implements CurrencyExchangeRateRepositoryInterface
{
    private const MAIN_CURRENCY_QUERY_PARAMETER_NAME = 'base';
    private const EXCHANGING_CURRENCY_QUERY_PARAMETER_NAME = 'symbols';

    /**
     * @throws ConnectionException
     */
    private function getExchangeResponse(string $url): Response
    {
        return Http::withOptions(['verify' => true])
            ->withHeader('apikey', config('app.exchangeServiceApiKey'))
            ->get($url);
    }

    private function getExchangeRateApiUrl(Currencies $currencyFrom, Currencies $currencyTo): string
    {
        $baseUrl = config('app.exchangeServiceApiHost') . '/';
        $urn = 'exchangerates_data' . '/' . 'latest';
        $currencyFromParameter = '?' . self::MAIN_CURRENCY_QUERY_PARAMETER_NAME . '=' . $currencyFrom->value . '&';
        $currencyToParameter = self::EXCHANGING_CURRENCY_QUERY_PARAMETER_NAME . '=' . $currencyTo->value;

        return $baseUrl . $urn . $currencyFromParameter . $currencyToParameter;
    }

    /**
     * @throws ConnectionException
     * @throws MalformedApiResponseException
     */
    public function getCurrentRate(Currencies $currencyFrom, Currencies $currencyTo): float
    {
        $url = $this->getExchangeRateApiUrl($currencyFrom, $currencyTo);

        $responseBodyArray = $this->getExchangeResponse($url)->json();

        if (!isset($responseBodyArray['rates'])) {
            throw new MalformedApiResponseException('Rates key not found in response body.');
        }

        $rate = $responseBodyArray['rates'];

        return $rate[$currencyTo->value];
    }
}
