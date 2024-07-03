<?php

namespace App\Repository\CurrencyExchange\RepositoryCreator;

use App\Repository\CurrencyExchange\ApiLayerCurrencyExchangeRateRepository;
use App\Repository\CurrencyExchange\CurrencyExchangeRateRepositoryInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Client\Factory as Http;
use Psr\Log\LoggerInterface;

class ApiLayerCurrencyExchangeRateRepositoryCreator implements CurrencyExchangeRateRepositoryCreatorInterface
{
    public function __construct(
        private Config $config,
        private Http $http,
        private LoggerInterface $logger
    ) {
    }
    public function create(): CurrencyExchangeRateRepositoryInterface
    {
        return new ApiLayerCurrencyExchangeRateRepository($this->config, $this->http, $this->logger);
    }
}
