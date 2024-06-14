<?php

namespace App\Service;

use App\DTO\VerificationDTO\BasicVerificationDTOInterface;
use App\Models\NotifiableInterface;

interface VerifiableInterface
{
    public function verify(): BasicVerificationDTOInterface;

    public function handleVerificationNotification(NotifiableInterface $notifiable): void;
}
