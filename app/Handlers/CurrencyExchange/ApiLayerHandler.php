<?php

namespace App\Handlers\CurrencyExchange;

use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use App\Service\CurrencyExchange\RepositoryCreator\CurrencyExchangeRateRepositoryCreatorInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class ApiLayerHandler extends AbstractHandler
{
    private CurrencyExchangeRateRepositoryInterface $currencyExchangeRepository;

    public function __construct(
        private CurrencyExchangeRateRepositoryCreatorInterface $currencyExchangeRateRepositoryCreator
    ) {
        $this->currencyExchangeRepository = $this->currencyExchangeRateRepositoryCreator->create();
    }

    public function handle(Currency $currencyFrom, Currency $currencyTo): ?ExchangeRateDTOInterface
    {
        try {
            return $this->currencyExchangeRepository->getCurrentRate($currencyFrom, $currencyTo);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return parent::handle($currencyFrom, $currencyTo);
        }
    }
}
