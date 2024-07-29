<?php

namespace App\Repository\Customer;

use App\DTO\CreationDTO\Customer\UpdateCustomerDTO;
use App\Exceptions\ModelNotExistsException;
use App\Exceptions\ModelNotSavedException;
use App\Exceptions\NotFoundException;
use App\Models\Customer;
use App\Models\Subscriber;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        private Customer $customer
    ) {
    }

    /**
     * @return Collection<int, Subscriber>
     */
    public function all(): Collection
    {
        return $this->customer->all();
    }

    /**
     * @throws NotFoundException
     */
    public function findById(string $id): Subscriber
    {
        $customer = $this->customer->findOrFail($id);
        if (!$customer) {
            throw new NotFoundException();
        }

        return $customer;
    }

    /**
     * @throws ModelNotSavedException
     */
    public function create(string $email): Subscriber
    {
        try {
            DB::beginTransaction();
            $customer = $this->customer->create([
                'email' => $email,
            ]);
            DB::commit();

            return $customer;
        } catch (Exception) {
            DB::rollBack();
            throw new ModelNotSavedException();
        }
    }

    /**
     * @throws NotFoundException|ModelNotSavedException
     */
    public function update(UpdateCustomerDTO $customerDTO): Subscriber
    {
        $customer = $this->findById($customerDTO->getId());
        try {
            DB::beginTransaction();
            $customer->update([
                'email' => $customerDTO->getEmail(),
            ]);
            DB::commit();

            return $customer;
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
        $customer = $this->findById($id);

        try {
            DB::beginTransaction();
            $isDeleted = $customer->delete();
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

    public function findByEmail(string $email): Subscriber
    {
        $customer = $this->customer->where('email', $email)->first();
        if (!$customer) {
            throw new NotFoundException();
        }

        return $customer;
    }
}
