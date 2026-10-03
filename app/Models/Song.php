<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Song extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'artist',
        'genre',
        'description',
        'audio_path',
        'cover_path',
        'duration',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}