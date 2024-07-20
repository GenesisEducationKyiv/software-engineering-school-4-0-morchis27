<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @method Subscriber|null findOrFail($id);
 * @method Subscriber create(array $data);
 * @method where(string $field, mixed $value)
 * @property string $id
 * @property Carbon $email_verified_at
 * @property string $email
 */
class Subscriber extends Model implements NotifiableInterface
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

    /**
     * @return string
     */
    public function getNotificationRefer(): string
    {
        return $this->email;
    }
}
