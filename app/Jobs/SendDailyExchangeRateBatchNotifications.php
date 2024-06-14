<?php

namespace App\Jobs;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Models\Subscriber;
use App\Service\DailyExchangeRateNewsLetterNotificationInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDailyExchangeRateBatchNotifications implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private DailyExchangeRateNewsLetterNotificationInterface $subscriptionService;

    /**
     * @param array<int, Subscriber> $subscribers
     * @param ExchangeRateDTO $exchangeRate
     */
    public function __construct(
        protected array $subscribers,
        private ExchangeRateDTO $exchangeRate,
    ) {
        $this->subscriptionService =
            app(DailyExchangeRateNewsLetterNotificationInterface::class);
    }

    public function handle(): void
    {
        foreach ($this->subscribers as $subscriber) {
            $this->subscriptionService->sendDailyExchangeRateNewsLetterNotification($subscriber, $this->exchangeRate);
        }
    }
}
