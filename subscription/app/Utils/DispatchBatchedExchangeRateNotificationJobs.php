<?php

namespace App\Utils;

use App\Enum\ConfigSpaceName;
use App\Models\Subscriber;
use App\Service\MessageBroker\MessageBrokerInterface;
use App\Services\Currency\CurrencyService;
use App\Services\Currency\CurrencyServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class DispatchBatchedExchangeRateNotificationJobs
{
    public function __invoke(): void
    {
        try {
            Log::info('started');
            /** @var CurrencyService $currencyExchangeRateService */
            $currencyExchangeRateService = resolve(CurrencyServiceInterface::class);

            $utilities = resolve(Utilities::class);
            $messageBroker = resolve(MessageBrokerInterface::class);
            $currencyExchangeRate = $currencyExchangeRateService
                ->getRate();
            Log::info('rate');

            Subscriber::query()
                ->whereNotNull('email_verified_at')
                ->chunk(
                    $utilities->getIntValueFromEnvVariable(
                        ConfigSpaceName::APP->value,
                        'dailyCurrencyExchangeRateNotificationJobBatchSIze'
                    ),
                    function ($subscribers) use ($currencyExchangeRate, $messageBroker, $utilities) {
                        Log::info('subscribers', [$subscribers]);
                        /** @var Collection<int, Subscriber> $subscribers */
                        foreach ($subscribers as $subscriber) {
//                            $messageBroker->publish(new DailyExchangeRateNotificationMessage(
//                                $subscriber,
//                                $currencyExchangeRate,
//                                $utilities
//                            ));
                        }
                    }
                );
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
