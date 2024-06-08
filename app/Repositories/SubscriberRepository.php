<?php

namespace App\Repositories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Collection;

class SubscriberRepository
{
    public function __construct(
        private Subscriber $subscriber
    )
    {
    }

    public function all(): Collection|array
    {
        return $this->subscriber->all();
    }

    public function findById(int $id): Subscriber
    {
        return $this->subscriber->find($id);
    }
}
