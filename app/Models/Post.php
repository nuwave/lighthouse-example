<?php

namespace App\Models;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Primary key
 * @property int $id
 *
 * Attributes
 * @property string $title
 * @property string $content
 *
 * Timestamps
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * Foreign keys
 * @property int $author_id
 *
 * Relations
 * @property-read \App\Models\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 *
 * @mixin \Eloquent
 */
final class Post extends Model
{
    protected static function booted(): void
    {
        self::creating(function (Post $post): void {
            if ($post->author()->doesntExist()) {
                $user = auth()->user()
                    ?? throw new AuthenticationException();
                $post->author()->associate($user);
            }
        });
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Comment, $this> */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
