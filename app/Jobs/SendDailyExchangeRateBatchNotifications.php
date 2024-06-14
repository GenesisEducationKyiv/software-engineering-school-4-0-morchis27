<?php

namespace App\Jobs;

use App\Models\Subscriber;
use App\Notifications\DailyExchangeRateNotification;
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

    /** @var array<int, Subscriber> $subscribers */
    protected array $subscribers;

    private float $exchangeRate;

    /**
     * @param array<int, Subscriber> $subscribers
     * @param float $exchangeRate
     */
    public function __construct(array $subscribers, float $exchangeRate)
    {
        $this->subscribers = $subscribers;
        $this->exchangeRate = $exchangeRate;
    }

    public function handle(): void
    {
        foreach ($this->subscribers as $subscriber) {
            $subscriber->notify(new DailyExchangeRateNotification($this->exchangeRate));
        }
    }
}
