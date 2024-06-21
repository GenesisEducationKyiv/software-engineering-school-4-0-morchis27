<?php

namespace App\Handlers\CurrencyExchange;


use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;
use App\Service\CurrencyExchange\Repository\PrivatCurrencyExchangeRateRepository;
use App\Service\CurrencyExchange\RepositoryCreator\CurrencyExchangeRateRepositoryCreatorInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class PrivatHandler extends AbstractHandler
{
    private PrivatCurrencyExchangeRateRepository $privatCurrencyExchangeRateRepository;

    public function __construct(
        private CurrencyExchangeRateRepositoryCreatorInterface $currencyExchangeRateRepositoryCreator
    ) {
        $this->privatCurrencyExchangeRateRepository = $this->currencyExchangeRateRepositoryCreator->create();
    }

    public function handle(Currency $currencyFrom, Currency $currencyTo): ?ExchangeRateDTOInterface
    {
        try {
            return $this->privatCurrencyExchangeRateRepository->getCurrentRate($currencyFrom, $currencyTo);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return parent::handle($currencyFrom, $currencyTo);
        }
    }
}
