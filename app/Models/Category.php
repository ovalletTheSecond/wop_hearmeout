<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function crushes(): HasMany
    {
        return $this->hasMany(Crush::class);
    }

    public function crushesMany(): BelongsToMany
    {
        return $this->belongsToMany(Crush::class, 'category_crush');
    }
}