<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'crush_id',
        'user_id',
        'text',
    ];

    public function crush(): BelongsTo
    {
        return $this->belongsTo(Crush::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(CommentReaction::class);
    }

    public function getLikesCount()
    {
        return $this->reactions()->where('type', 'like')->count();
    }

    public function getDislikesCount()
    {
        return $this->reactions()->where('type', 'dislike')->count();
    }
}
