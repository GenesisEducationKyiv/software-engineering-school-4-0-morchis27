<?php

namespace App\Service\Customer;

use App\Repository\Customer\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CustomerService implements CustomerServiceInterface
{
    public function __construct(
        private CustomerRepositoryInterface $repository,
    ) {
    }

    public function create(string $email): void
    {
        Log::info('asdasd');
        $this->repository->create($email);
    }

    public function deleteByEmail(string $email): bool
    {
        return $this->repository->delete($this->repository->findByEmail($email)->id);
    }
}
