<?php

namespace App\Service\CurrencyExchange\RepositoryCreator;

use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use App\Service\CurrencyExchange\Repository\PrivatCurrencyExchangeRateRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Client\Factory as HttpClientFactory;
use Psr\Log\LoggerInterface;

class PrivatCurrencyExchangeRateRepositoryCreator implements CurrencyExchangeRateRepositoryCreatorInterface
{
    public function __construct(
        private ConfigRepository $config,
        private HttpClientFactory $http,
        private LoggerInterface $logger

    ) {
    }

    public function create(): CurrencyExchangeRateRepositoryInterface
    {
        return new PrivatCurrencyExchangeRateRepository($this->config, $this->http, $this->logger);
    }
}
