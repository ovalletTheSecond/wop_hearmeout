<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'content', 'read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}