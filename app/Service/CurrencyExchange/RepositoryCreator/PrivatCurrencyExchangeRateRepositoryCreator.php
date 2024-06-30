<?php

namespace App\Service\CurrencyExchange\RepositoryCreator;

use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use App\Service\CurrencyExchange\Repository\PrivatCurrencyExchangeRateRepository;
use App\Utils\Utilities;
use Illuminate\Http\Client\Factory as HttpClientFactory;
use Psr\Log\LoggerInterface;

class PrivatCurrencyExchangeRateRepositoryCreator implements CurrencyExchangeRateRepositoryCreatorInterface
{
    public function __construct(
        private HttpClientFactory $http,
        private LoggerInterface $logger,
        private Utilities $utilities,
    ) {
    }

    public function create(): CurrencyExchangeRateRepositoryInterface
    {
        return new PrivatCurrencyExchangeRateRepository($this->http, $this->logger, $this->utilities);
    }
}
