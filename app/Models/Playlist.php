<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'name',
        'cover_path',
        'cover_color',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELASI — USER (owner)
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI — SONGS (many to many lewat playlist_songs)
    |--------------------------------------------------------------------------
    */

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'playlist_songs')
            ->withTimestamps();
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSOR — COVER URL
    |--------------------------------------------------------------------------
    |
    | Kalau cover_path ada, return URL storage.
    | Kalau nggak ada, return null (nanti di view fallback ke warna solid).
    |
    */

    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover_path) {
            return asset('storage/' . $this->cover_path);
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER — TOTAL DURASI
    |--------------------------------------------------------------------------
    |
    | Jumlahin durasi semua lagu di playlist (dalam detik).
    |
    */

    public function getTotalDurationAttribute(): int
    {
        return (int) $this->songs->sum('duration');
    }
}