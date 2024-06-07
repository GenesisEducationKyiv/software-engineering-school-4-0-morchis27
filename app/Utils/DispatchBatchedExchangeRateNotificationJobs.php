<?php

namespace App\Utils;

use App\Enum\Currencies;
use App\Jobs\SendDailyExchangeRateBatchNotifications;
use App\Models\Subscriber;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use Illuminate\Database\Eloquent\Collection;

class DispatchBatchedExchangeRateNotificationJobs
{
    private const BATCH_SIZE = 500;

    public function __invoke(): void
    {
        try {
            $currencyExchangeRateService = resolve(CurrencyExchangeRateService::class);

            $currencyExchangeRate = $currencyExchangeRateService
                ->getCurrentRate(Currencies::USD, Currencies::UAH);

            Subscriber::query()
                ->whereNotNull('email_verified_at')
                ->chunk(self::BATCH_SIZE, function ($subscribers) use ($currencyExchangeRate) {
                    /** @var Collection $subscribers */
                    dispatch(new SendDailyExchangeRateBatchNotifications($subscribers->all(), $currencyExchangeRate));
                });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
