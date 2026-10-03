<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlaylistController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX — daftar playlist user
    |--------------------------------------------------------------------------
    |
    | Kalau di-request via fetch (Accept: application/json),
    | return JSON. Kalau via browser, return view.
    |
    */

    public function index(Request $request)
    {
        $playlists = Playlist::withCount('songs')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        /*
        | Return JSON untuk context menu di Home
        */

        if ($request->wantsJson()) {
            return response()->json([
                'playlists' => $playlists->map(function ($playlist) {
                    return [
                        'id' => $playlist->id,
                        'name' => $playlist->name,
                        'cover_color' => $playlist->cover_color,
                        'cover_path' => $playlist->cover_path,
                        'cover_url' => $playlist->cover_path
                            ? asset('storage/' . $playlist->cover_path)
                            : null,
                        'songs_count' => $playlist->songs_count,
                    ];
                }),
            ]);
        }

        /*
        | Return view untuk browser
        */

        return view('playlist.index', compact('playlists'));
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW — detail playlist
    |--------------------------------------------------------------------------
    */

    public function show(Playlist $playlist)
    {
        $playlist->load([
            'songs' => function ($q) {
                $q->orderBy('playlist_songs.created_at', 'asc');
            },
            'user',
        ]);

        $isOwner = $playlist->user_id === Auth::id();

        return view('playlist.show', compact('playlist', 'isOwner'));
    }


    /*
    |--------------------------------------------------------------------------
    | STORE — bikin playlist baru
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $colors = [
            '#c5a45c',
            '#e07a5f',
            '#3d5a80',
            '#81b29a',
            '#9a8c98',
            '#6d597a',
        ];

        $playlist = Playlist::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'cover_color' => $colors[array_rand($colors)],
        ]);

        /*
        | Return JSON kalau dari fetch
        */

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'playlist' => [
                    'id' => $playlist->id,
                    'name' => $playlist->name,
                    'cover_color' => $playlist->cover_color,
                    'cover_path' => $playlist->cover_path,
                    'songs_count' => 0,
                ],
            ]);
        }

        /*
        | Return redirect kalau dari form biasa
        */

        return redirect()
            ->route('playlist.show', $playlist)
            ->with('success', 'Playlist created!');
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY — hapus playlist
    |--------------------------------------------------------------------------
    */

    public function destroy(Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        if ($playlist->cover_path) {
            Storage::disk('public')->delete($playlist->cover_path);
        }

        $playlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Playlist deleted.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UPLOAD COVER — upload/ganti cover playlist
    |--------------------------------------------------------------------------
    */

    public function uploadCover(Request $request, Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $request->validate([
            'cover' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        /*
        | Hapus cover lama kalau ada
        */

        if ($playlist->cover_path) {
            Storage::disk('public')->delete($playlist->cover_path);
        }

        /*
        | Simpan cover baru
        */

        $path = $request->file('cover')->store('playlist-covers', 'public');

        $playlist->update(['cover_path' => $path]);

        /*
        | Return JSON kalau dari fetch
        */

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cover_url' => asset('storage/' . $path),
            ]);
        }

        /*
        | Return redirect kalau dari form
        */

        return redirect()
            ->route('playlist.show', $playlist)
            ->with('success', 'Cover updated!');
    }


    /*
    |--------------------------------------------------------------------------
    | ADD SONG — tambah lagu ke playlist
    |--------------------------------------------------------------------------
    */

    public function addSong(Playlist $playlist, Song $song)
    {
        if ($playlist->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $playlist->songs()->syncWithoutDetaching([$song->id]);

        return response()->json([
            'success' => true,
            'message' => 'Added to playlist.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE SONG — hapus lagu dari playlist
    |--------------------------------------------------------------------------
    */

    public function removeSong(Playlist $playlist, Song $song)
    {
        if ($playlist->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $playlist->songs()->detach($song->id);

        return response()->json([
            'success' => true,
            'message' => 'Removed from playlist.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FAVORITES PAGE
    |--------------------------------------------------------------------------
    |
    | Nampilin semua lagu yang di-favorite user.
    |
    */

    public function favorites()
    {
        $songs = Song::whereIn('id', function ($q) {
                $q->select('song_id')
                  ->from('favorites')
                  ->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        /*
        | Ambil semua song_id yang difavorite untuk checkbox di view
        */

        $favorites = $songs->pluck('id')->toArray();

        return view('playlist.favorites', compact('songs', 'favorites'));
    }
}