<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\RecentlyPlayed;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX — Show All Songs
    |--------------------------------------------------------------------------
    | Query: ?filter=recommended|recent|all
    */

    public function index(Request $request)
    {
        $userId = Auth::id();
        $filter = $request->query('filter', 'all');

        $favorites = Favorite::where('user_id', $userId)
            ->pluck('song_id')
            ->toArray();

        $title = 'All Music';
        $subtitle = 'Semua lagu yang ada di Wavo';
        $songs = collect();

        if ($filter === 'recommended') {

            $title = 'Recommended';
            $subtitle = 'Non-stop music based on your favorite songs and artists.';

            $recentSongs = RecentlyPlayed::with('song')
                ->where('user_id', $userId)
                ->orderByDesc('played_at')
                ->limit(1)
                ->get()
                ->pluck('song')
                ->filter();

            $lastPlayed = $recentSongs->first();

            if ($lastPlayed) {
                $songs = Song::where('genre', $lastPlayed->genre)
                    ->where('id', '!=', $lastPlayed->id)
                    ->latest()
                    ->get();
            }

            if ($songs->count() === 0) {
                $songs = Song::latest()->get();
            }

        } elseif ($filter === 'recent') {

            $title = 'Recently Played';
            $subtitle = 'Inspired by your recent activity';

            $songs = RecentlyPlayed::with('song')
                ->where('user_id', $userId)
                ->orderByDesc('played_at')
                ->get()
                ->pluck('song')
                ->filter();

        } else {

            $title = 'All Music';
            $subtitle = 'Semua lagu yang ada di Wavo';
            $songs = Song::with('user')->latest()->get();
        }

        return view('songs', compact(
            'songs',
            'favorites',
            'title',
            'subtitle',
            'filter'
        ));
    }
}