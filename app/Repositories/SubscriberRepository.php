<?php

namespace App\Repositories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Collection;

class SubscriberRepository
{
    public function __construct(
        private Subscriber $subscriber
    ) {
    }

    /**
     * @return Collection<int, Subscriber>|array<Subscriber>
     */
    public function all(): Collection|array
    {
        return $this->subscriber->all();
    }

    public function findById(int $id): Subscriber|null
    {
        return $this->subscriber->find($id);
    }
}
