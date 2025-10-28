<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = [
        'crush_id',
        'vote_type',
        'ip_address',
        'session_id',
        'stats_version',
    ];

    public function crush(): BelongsTo
    {
        return $this->belongsTo(Crush::class);
    }
}
