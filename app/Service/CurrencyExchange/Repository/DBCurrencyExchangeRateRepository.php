<?php

namespace App\Service\CurrencyExchange\Repository;

use App\Enum\Currencies;

//THIS CLASS IS AN EXAMPLE OF WHY WE USED AN INTERFACE IN THE FIRST PLACE
class DBCurrencyExchangeRateRepository implements CurrencyExchangeRateRepositoryInterface
{
    public function getCurrentRate(Currencies $currencyFrom, Currencies $currencyTo): float
    {
        //here would go implementation of a different source(DB) from which we could've grabbed our data
        return 0;
    }
}
