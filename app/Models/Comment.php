<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Primary key
 * @property int $id
 *
 * Attributes
 * @property string $reply
 *
 * Timestamps
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * Foreign keys
 * @property int $post_id
 *
 * Relations
 * @property-read \App\Models\Post $post
 *
 * @mixin \Eloquent
 */
final class Comment extends Model
{
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
