<?php

namespace App\Services\CurrencyExchange;

use Illuminate\Support\Facades\Http;

readonly final class CurrencyService implements CurrencyServiceInterface
{
    public function getRate(): float
    {
        return (float) Http::get('http://currency_nginx:80/api/rate')->body();
    }
}
