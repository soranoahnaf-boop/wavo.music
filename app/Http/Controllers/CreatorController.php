<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreatorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $songs = Song::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('creator', compact('songs'));
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'audio' => 'required|mimes:mp3,wav,ogg,m4a|max:50000',
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'artist' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'lyrics' => 'nullable|string|max:60000',
            'duration' => 'nullable|integer|min:0',
            'agreement' => 'required|accepted',
        ]);

        $coverPath = null;

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $audioPath = $request->file('audio')->store('music', 'public');

        Song::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'genre' => $validated['genre'],
            'artist' => $validated['artist'],
            'description' => $validated['description'] ?? null,
            'lyrics' => $validated['lyrics'] ?? null,
            'cover_path' => $coverPath,
            'audio_path' => $audioPath,
            'duration' => $validated['duration'] ?? null,
        ]);

        return redirect()
            ->route('creator')
            ->with('success', 'Song added successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    | Support 2 mode:
    | - AJAX (Accept: application/json) → return JSON
    | - Form (biasa) → redirect back
    */

    public function destroy(Song $song, Request $request)
    {
        if ($song->user_id !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 403);
            }

            abort(403);
        }

        if ($song->cover_path) {
            Storage::disk('public')->delete($song->cover_path);
        }

        if ($song->audio_path) {
            Storage::disk('public')->delete($song->audio_path);
        }

        $song->delete();

        // AJAX mode
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Song deleted.',
            ]);
        }

        // Form mode
        return redirect()
            ->route('creator')
            ->with('success', 'Song deleted successfully!');
    }
}