<?php

namespace App\Events;

use App\Models\Subscriber;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Subscribed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Subscriber $subscriber,
    )
    {
    }
}
