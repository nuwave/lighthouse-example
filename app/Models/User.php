<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Primary key
 * @property int $id
 *
 * Attributes
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 *
 * Timestamps
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * Relations
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<\Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 *
 * @method static UserFactory factory(...$parameters)
 *
 * @mixin \Eloquent
 */
final class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Post> */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}
