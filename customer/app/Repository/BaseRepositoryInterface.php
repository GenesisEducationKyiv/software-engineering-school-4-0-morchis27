<?php

namespace App\Repository;

use App\DTO\CreationDTO\Customer\UpdateCustomerDTO;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
    /**
     * @param string $id
     * @return Customer
     */
    public function findById(string $id): Customer;

    /**
     * @return Collection<int, Customer>
     */
    public function all(): Collection;

    /**
     * @param string $email
     * @return Customer
     */
    public function create(string $email): Customer;

    /**
     * @param UpdateCustomerDTO $customerDTO
     * @return Customer
     */
    public function update(UpdateCustomerDTO $customerDTO): Customer;

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    public function findByEmail(string $email): Customer;
}
