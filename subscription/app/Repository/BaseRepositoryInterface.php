<?php

namespace App\Repository;

use App\DTO\CreationDTO\Subscriber\CreateSubscriberDTO;
use App\DTO\CreationDTO\Subscriber\UpdateSubscriberDTO;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
    /**
     * @param string $id
     * @return Subscriber
     */
    public function findById(string $id): Subscriber;

    /**
     * @return Collection<int, Subscriber>
     */
    public function all(): Collection;

    /**
     * @param CreateSubscriberDTO $createSubscriberDTO
     * @return Subscriber
     */
    public function create(CreateSubscriberDTO $createSubscriberDTO): Subscriber;

    /**
     * @param UpdateSubscriberDTO $subscriberDTO
     * @return Subscriber
     */
    public function update(UpdateSubscriberDTO $subscriberDTO): Subscriber;

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}
