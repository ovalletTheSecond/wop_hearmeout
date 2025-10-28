<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crush extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'text',
        'image_path',
        'stats_version',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function getVoteStats()
    {
        $currentVersion = $this->stats_version;
        return [
            'oui' => $this->votes()->where('vote_type', 'oui')->where('stats_version', $currentVersion)->count(),
            'non' => $this->votes()->where('vote_type', 'non')->where('stats_version', $currentVersion)->count(),
            'non_tare' => $this->votes()->where('vote_type', 'non_tare')->where('stats_version', $currentVersion)->count(),
            'tare_mais_oui' => $this->votes()->where('vote_type', 'tare_mais_oui')->where('stats_version', $currentVersion)->count(),
        ];
    }
}
