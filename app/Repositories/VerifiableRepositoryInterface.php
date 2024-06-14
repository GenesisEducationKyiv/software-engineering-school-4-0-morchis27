<?php

namespace App\Repositories;

interface VerifiableRepositoryInterface
{
    /**
     * @return void
     */
    public function verify(): void;

    /**
     * @return bool
     */
    public function isVerified(): bool;
}
