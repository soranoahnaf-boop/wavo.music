<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Playlist;
use App\Models\RecentlyPlayed;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePagexController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HOME
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        if (!Auth::check()) {
            return view('homepage', [
                'songs' => collect(),
                'favorites' => [],
                'recentSongs' => collect(),
                'recommendedSongs' => collect(),
                'playlists' => collect(),
                'artists' => collect(),
            ]);
        }

        $userId = Auth::id();

        $songs = Song::with('user')->latest()->get();

        $favorites = Favorite::where('user_id', $userId)
            ->pluck('song_id')
            ->toArray();

        $recentSongs = RecentlyPlayed::with('song')
            ->where('user_id', $userId)
            ->orderByDesc('played_at')
            ->limit(20)
            ->get()
            ->pluck('song')
            ->filter();

        // Recommended
        $recommendedSongs = collect();
        $lastPlayed = $recentSongs->first();

        if ($lastPlayed) {
            $recommendedSongs = Song::where('genre', $lastPlayed->genre)
                ->where('id', '!=', $lastPlayed->id)
                ->latest()
                ->limit(20)
                ->get();
        }

        if ($recommendedSongs->count() === 0) {
            $recommendedSongs = Song::latest()->limit(20)->get();
        }

        // Playlists
        $playlists = Playlist::withCount('songs')
            ->where('user_id', $userId)
            ->latest()
            ->limit(6)
            ->get();

        // Artists
        $artists = Song::select('artist')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('artist')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        return view('homepage', compact(
            'songs',
            'favorites',
            'recentSongs',
            'recommendedSongs',
            'playlists',
            'artists'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | FAVORITE TOGGLE
    |--------------------------------------------------------------------------
    */

    public function favorite(Song $song)
    {
        $userId = Auth::id();

        $favorite = Favorite::where('user_id', $userId)
            ->where('song_id', $song->id)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'favorited' => false,
            ]);
        }

        Favorite::create([
            'user_id' => $userId,
            'song_id' => $song->id,
        ]);

        return response()->json([
            'success' => true,
            'favorited' => true,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | RECENTLY PLAYED
    |--------------------------------------------------------------------------
    */

    public function played(Song $song)
    {
        $userId = Auth::id();

        $recent = RecentlyPlayed::where('user_id', $userId)
            ->where('song_id', $song->id)
            ->first();

        if ($recent) {
            $recent->update(['played_at' => now()]);
        } else {
            RecentlyPlayed::create([
                'user_id' => $userId,
                'song_id' => $song->id,
                'played_at' => now(),
            ]);
        }

        $oldRecords = RecentlyPlayed::where('user_id', $userId)
            ->orderByDesc('played_at')
            ->skip(20)
            ->take(100)
            ->get();

        foreach ($oldRecords as $oldRecord) {
            $oldRecord->delete();
        }

        return response()->json(['success' => true]);
    }
}