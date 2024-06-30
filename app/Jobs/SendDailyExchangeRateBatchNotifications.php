<?php

namespace App\Jobs;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Models\Subscriber;
use App\Service\DailyExchangeRateNewsLetterNotificationInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

class SendDailyExchangeRateBatchNotifications implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private DailyExchangeRateNewsLetterNotificationInterface $subscriptionService;
    private LoggerInterface $logger;

    /**
     * @param array<int, Subscriber> $subscribers
     * @param ExchangeRateDTOInterface|null $exchangeRate
     */
    public function __construct(
        protected array $subscribers,
        private ?ExchangeRateDTOInterface $exchangeRate,
    ) {
        $this->subscriptionService =
            app(DailyExchangeRateNewsLetterNotificationInterface::class);
        $this->logger = app(LoggerInterface::class);
    }

    public function handle(): void
    {
        if (!$this->exchangeRate) {
            $this->logger->info('Couldnt send an email due to exchange rate being null', $this->subscribers);
            return;
        }

        foreach ($this->subscribers as $subscriber) {
            $this->subscriptionService->sendDailyExchangeRateNewsLetterNotification($subscriber, $this->exchangeRate);
        }
    }
}
