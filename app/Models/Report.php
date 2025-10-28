<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'crush_id',
        'ip_address',
        'reason',
    ];

    public function crush(): BelongsTo
    {
        return $this->belongsTo(Crush::class);
    }
}
