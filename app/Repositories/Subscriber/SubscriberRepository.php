<?php

namespace App\Repositories\Subscriber;

use App\DTO\CreationDTO\Subscriber\CreateSubscriberDTO;
use App\DTO\CreationDTO\Subscriber\UpdateSubscriberDTO;
use App\Exceptions\ModelNotExistsException;
use App\Exceptions\ModelNotSavedException;
use App\Exceptions\NotFoundException;
use App\Exceptions\NotVerifiedException;
use App\Models\NotifiableInterface;
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
     * @throws NotFoundException|ModelNotSavedException
     */
    public function update(UpdateSubscriberDTO $subscriberDTO): Subscriber
    {
        $subscriber = $this->findById($subscriberDTO->getId());
        try {
            DB::beginTransaction();
            $subscriber->update([
                'email' => $subscriberDTO->getEmail(),
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
     * @throws ModelNotExistsException
     */
    public function delete(string $id): bool
    {
        $subscriber = $this->findById($id);

        try {
            DB::beginTransaction();
            $isDeleted = $subscriber->delete();
            if ($isDeleted === null) {
                throw new ModelNotExistsException();
            }

            DB::commit();

            return $isDeleted;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @return void
     * @throws NotVerifiedException
     */
    public function verify(NotifiableInterface $subscriber): void
    {
        try {
            // @phpstan-ignore-next-line
            $subscriber->markEmailAsVerified();
        } catch (Exception) {
            throw new NotVerifiedException();
        }
    }

    /**
     * @return bool
     */
    public function isVerified(NotifiableInterface $subscriber): bool
    {
        // @phpstan-ignore-next-line
        return $subscriber->hasVerifiedEmail();
    }
}
