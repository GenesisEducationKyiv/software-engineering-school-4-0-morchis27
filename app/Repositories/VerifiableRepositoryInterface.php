<?php

namespace App\Repositories;

use App\Models\NotifiableInterface;

interface VerifiableRepositoryInterface
{
    /**
     * @param NotifiableInterface $subscriber
     * @return void
     */
    public function verify(NotifiableInterface $subscriber): void;

    /**
     * @param NotifiableInterface $subscriber
     * @return bool
     */
    public function isVerified(NotifiableInterface $subscriber): bool;
}
