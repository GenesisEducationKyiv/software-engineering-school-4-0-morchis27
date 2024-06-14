<?php

namespace App\Utils;

use App\Enum\ConfigSpaceName;
use App\Enum\Currency;
use App\Jobs\SendDailyExchangeRateBatchNotifications;
use App\Models\Subscriber;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class DispatchBatchedExchangeRateNotificationJobs
{
    public function __invoke(): void
    {
        try {
            $currencyExchangeRateService = resolve(CurrencyExchangeRateService::class);

            $currencyExchangeRate = $currencyExchangeRateService
                ->getCurrentRate(Currency::USD, Currency::UAH);

            Subscriber::query()
                ->whereNotNull('email_verified_at')
                ->chunk(
                    Utilities::getIntValueFromEnvVariable(
                        ConfigSpaceName::APP->value,
                        'dailyCurrencyExchangeRateNotificationJobBatchSIze'
                    ),
                    function ($subscribers) use ($currencyExchangeRate) {
                        /** @var Collection<int, Subscriber> $subscribers */
                        dispatch(new SendDailyExchangeRateBatchNotifications(
                            $subscribers->all(),
                            $currencyExchangeRate
                        ));
                    }
                );
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
