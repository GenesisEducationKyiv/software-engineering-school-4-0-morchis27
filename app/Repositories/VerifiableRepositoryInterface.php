<?php

namespace App\Repositories;

use App\Models\NotifiableInterface;
use App\Models\Subscriber;

interface VerifiableRepositoryInterface
{
    /**
     * @param Subscriber $subscriber
     * @return void
     */
    public function verify(NotifiableInterface $subscriber): void;

    /**
     * @param Subscriber $subscriber
     * @return bool
     */
    public function isVerified(NotifiableInterface $subscriber): bool;
}
