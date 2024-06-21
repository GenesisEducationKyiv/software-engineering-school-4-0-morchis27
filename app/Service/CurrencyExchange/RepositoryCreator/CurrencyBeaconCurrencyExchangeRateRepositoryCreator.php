<?php

namespace App\Service\CurrencyExchange\RepositoryCreator;

use App\Service\CurrencyExchange\Repository\CurrencyBeaconCurrencyExchangeRateRepository;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Contracts\Config\Repository as Config;
use Psr\Log\LoggerInterface;

class CurrencyBeaconCurrencyExchangeRateRepositoryCreator implements CurrencyExchangeRateRepositoryCreatorInterface
{
    public function __construct(
        private Config $config,
        private Http $http,
        private LoggerInterface $logger
    ) {
    }

    public function create(): CurrencyExchangeRateRepositoryInterface
    {
        return new CurrencyBeaconCurrencyExchangeRateRepository($this->config, $this->http, $this->logger);
    }
}
