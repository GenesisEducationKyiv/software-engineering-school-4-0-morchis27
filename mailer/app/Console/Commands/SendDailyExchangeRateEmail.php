<?php

namespace App\Console\Commands;

use App\Notifications\DailyCurrencyExchangeEmail;
use App\Services\CurrencyExchange\CurrencyServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;
use Junges\Kafka\Facades\Kafka;

class SendDailyExchangeRateEmail extends Command
{

    public function __construct(
        private CurrencyServiceInterface $currencyService,
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $consumer = Kafka::consumer()
                ->subscribe('daily-mail-requests')
                ->withAutoCommit(false)
                ->enableBatching()
                ->withBatchSizeLimit(500)
                ->withBatchReleaseInterval(1500)
                ->withHandler(function (
                    Collection $collection,
                    MessageConsumer $consumer
                ) {
                    $currencyExchangeRate = $this->currencyService->getRate();
                    /** @var ConsumerMessage $message */
                    foreach ($collection as $message) {
                        Notification::route('mail',
                            $message->getBody()['data']['to'])
                            ->notify(new DailyCurrencyExchangeEmail($currencyExchangeRate));
                    }
                })
                ->build();

            $consumer->consume();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
