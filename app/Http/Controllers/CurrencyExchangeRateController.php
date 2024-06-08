<?php

namespace App\Http\Controllers;

use App\Enum\Currencies;
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
            $this->currencyExchangeRateService->getCurrentRate(Currencies::USD, Currencies::UAH)
        );
    }
}
