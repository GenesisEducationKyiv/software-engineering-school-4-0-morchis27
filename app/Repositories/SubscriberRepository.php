<?php

namespace App\Repositories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_Subscriber_C;

class SubscriberRepository
{
    public function __construct(
        private Subscriber $subscriber
    )
    {
    }

    public function all(): Collection|array|_IH_Subscriber_C
    {
        return $this->subscriber->all();
    }

    public function findById(int $id): Subscriber
    {
        return $this->subscriber->find($id);
    }
}
