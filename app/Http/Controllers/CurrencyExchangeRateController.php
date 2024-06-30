<?php

namespace App\Http\Controllers;

use App\Enum\Currency;
use App\Service\CurrencyExchange\CurrencyExchangeRateInterface;
use Illuminate\Http\JsonResponse;

class CurrencyExchangeRateController extends Controller
{
    public function __construct(
        private CurrencyExchangeRateInterface $currencyExchangeRateService
    ) {
    }

    public function getExchangeRate(): JsonResponse
    {
        return $this->successResponse(
            $this->currencyExchangeRateService
                ->getCurrentRate(Currency::USD, Currency::UAH)
                ?->getSellExchangeRate()
        );
    }
}
