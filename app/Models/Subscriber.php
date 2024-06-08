<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $id
 * @property string $email_verified_at
 * @property string $email
 */
class Subscriber extends Model
{
    use HasFactory;
    use Notifiable;
    use HasUuids;
    use MustVerifyEmail;

    protected $fillable = [
        'email',
    ];


    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
