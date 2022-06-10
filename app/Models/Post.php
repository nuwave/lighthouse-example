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
    protected static function boot()
    {
        parent::boot();

        self::creating(function (Post $post): void {
            if ($post->author_id === null) {
                $user = auth()->user();
                if ($user === null) {
                    throw new AuthenticationException();
                }
                assert($user instanceof User);

                $post->author_id = $user->id;
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
