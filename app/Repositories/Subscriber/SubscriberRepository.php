<?php

namespace App\Repositories\Subscriber;

use App\DTO\CreateSubscriberDTO;
use App\DTO\UpdateSubscriberDTO;
use App\Exceptions\ModelNotExistsException;
use App\Exceptions\ModelNotSavedException;
use App\Exceptions\NotFoundException;
use App\Models\Subscriber;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SubscriberRepository implements SubscriberRepositoryInterface
{
    public function __construct(
        private Subscriber $subscriber
    ) {
    }

    /**
     * @return Collection<int, Subscriber>
     */
    public function all(): Collection
    {
        return $this->subscriber->all();
    }

    /**
     * @throws NotFoundException
     */
    public function findById(string $id): Subscriber
    {
        $subscriber = $this->subscriber->find($id);
        if (!$subscriber) {
            throw new NotFoundException();
        }

        return $subscriber;
    }

    /**
     * @throws ModelNotSavedException
     */
    public function create(CreateSubscriberDTO $createSubscriberDTO): Subscriber
    {
        try {
            DB::beginTransaction();
            $subscriber = $this->subscriber->create([
                'email' => $createSubscriberDTO->getEmail(),
            ]);
            DB::commit();

            return $subscriber;
        } catch (Exception) {
            DB::rollBack();
            throw new ModelNotSavedException();
        }
    }

    /**
     * @throws NotFoundException
     */
    public function update(UpdateSubscriberDTO $subscriberDTO): Subscriber
    {
        $subscriber = $this->findById($subscriberDTO->getId());
        $subscriber->update([
            'email' => $subscriberDTO->getEmail(),
        ]);

        return $subscriber;
    }

    /**
     * @throws NotFoundException
     * @throws ModelNotExistsException
     */
    public function delete(string $id): bool
    {
        $subscriber = $this->findById($id);

        $isDeleted = $subscriber->delete();
        if ($isDeleted === null) {
            throw new ModelNotExistsException();
        }

        return $isDeleted;
    }
}
