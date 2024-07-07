<?php

namespace App\Repositories\CurrencyExchange\RepositoryCreator;

use App\Repositories\CurrencyExchange\CurrencyExchangeRateRepositoryInterface;
use App\Repositories\CurrencyExchange\PrivatCurrencyExchangeRateRepository;
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
