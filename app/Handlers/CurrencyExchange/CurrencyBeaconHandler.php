<?php

namespace App\Handlers\CurrencyExchange;

use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Repositories\CurrencyExchange\CurrencyExchangeRateRepositoryInterface;
use App\Repositories\CurrencyExchange\RepositoryCreator\CurrencyExchangeRateRepositoryCreatorInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class CurrencyBeaconHandler extends AbstractHandler
{
    private CurrencyExchangeRateRepositoryInterface $currencyExchangeRateRepository;
    public function __construct(
        private CurrencyExchangeRateRepositoryCreatorInterface $currencyExchangeRateRepositoryCreator
    ) {
        $this->currencyExchangeRateRepository = $this->currencyExchangeRateRepositoryCreator->create();
    }
    public function handle(Currency $currencyFrom, Currency $currencyTo): ?ExchangeRateDTOInterface
    {
        try {
            return $this->currencyExchangeRateRepository->getCurrentRate($currencyFrom, $currencyTo);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return parent::handle($currencyFrom, $currencyTo);
        }
    }
}
