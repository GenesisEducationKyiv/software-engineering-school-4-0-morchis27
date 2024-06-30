<?php

namespace App\Models;

interface NotifiableInterface
{
    //don't really like this solution any tips? What i
    // propose is that we implement our own notify method instead of using laravel's trait
    /**
     * @param mixed $instance
     * @return void
     */
    public function notify(mixed $instance);

    public function getNotificationRefer(): string;
}
