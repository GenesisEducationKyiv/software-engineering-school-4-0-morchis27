<?php

namespace App\Service;

use App\DTO\VerificationDTO\BasicVerificationDTOInterface;
use App\Models\NotifiableInterface;
use App\Models\Subscriber;

interface VerifiableInterface
{
    public function verify(NotifiableInterface $subscriber): BasicVerificationDTOInterface;

    public function handleVerificationNotification(NotifiableInterface $notifiable): void;
}
