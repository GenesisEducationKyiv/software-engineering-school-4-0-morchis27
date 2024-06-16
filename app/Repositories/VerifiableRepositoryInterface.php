<?php

namespace App\Repositories;

use App\Models\Subscriber;

interface VerifiableRepositoryInterface
{
    /**
     * @param Subscriber $subscriber
     * @return void
     */
    public function verify(Subscriber $subscriber): void;

    /**
     * @param Subscriber $subscriber
     * @return bool
     */
    public function isVerified(Subscriber $subscriber): bool;
}
