<?php

namespace App\Events;

use App\Models\Subscriber;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Subscribed
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Subscriber $subscriber,
    ) {
    }
}
