<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil Query
        |--------------------------------------------------------------------------
        */

        $query = trim($request->input('q', ''));

        /*
        |--------------------------------------------------------------------------
        | Cek Apakah Yang Dipilih Adalah Genre
        |--------------------------------------------------------------------------
        */

        $genres = [
            'Pop',
            'Rock',
            'Hip Hop',
            'Rap',
            'R&B',
            'Soul',
            'Funk',
            'Jazz',
            'Blues',
            'Classical',
            'Country',
            'Folk',
            'Reggae',
            'Gospel',
            'Electronic',
            'EDM',
            'House',
            'Techno',
            'Trance',
            'Dubstep',
            'Drum & Bass',
            'Ambient',
            'Lo-fi',
            'Metal',
            'Punk',
            'Alternative',
            'Indie',
            'K-Pop',
            'J-Pop',
            'C-Pop',
            'Anime',
            'Soundtrack',
            'Instrumental',
            'Acoustic',
            'Other'
        ];

        $isGenre = in_array($query, $genres, true);

        /*
        |--------------------------------------------------------------------------
        | Ambil Lagu
        |--------------------------------------------------------------------------
        */

        $songs = collect();

        if ($query !== '' && Auth::check()) {

            /*
            |--------------------------------------------------------------------------
            | Kalau Klik Genre
            |--------------------------------------------------------------------------
            */

            if ($isGenre) {

                $songs = Song::where('user_id', Auth::id())
                    ->where('genre', $query)
                    ->latest()
                    ->get();

            } else {

                /*
                |--------------------------------------------------------------------------
                | Search Biasa
                |--------------------------------------------------------------------------
                |
                | Mencari berdasarkan:
                | - Judul
                | - Artist
                | - Genre
                |
                */

                $songs = Song::where('user_id', Auth::id())
                    ->where(function ($search) use ($query) {

                        $search->where('title', 'like', '%' . $query . '%')
                            ->orWhere('artist', 'like', '%' . $query . '%')
                            ->orWhere('genre', 'like', '%' . $query . '%');

                    })
                    ->latest()
                    ->get();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kirim Data ke Search
        |--------------------------------------------------------------------------
        */

        return view('search', compact(
            'songs',
            'query'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | AUTOCOMPLETE / SEARCH SUGGESTION
    |--------------------------------------------------------------------------
    |
    | Method ini dipanggil JavaScript ketika user mengetik
    | minimal 1 huruf di search bar.
    |
    */

    public function suggest(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | User harus login
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            return response()->json([]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil input
        |--------------------------------------------------------------------------
        */

        $query = trim($request->input('q', ''));

        /*
        |--------------------------------------------------------------------------
        | Kalau kosong
        |--------------------------------------------------------------------------
        */

        if ($query === '') {
            return response()->json([]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cari lagu milik user yang sedang login
        |--------------------------------------------------------------------------
        |
        | Pencarian berdasarkan:
        | - Awalan judul
        | - Awalan artist
        | - Awalan genre
        |
        */

        $songs = Song::where('user_id', Auth::id())
            ->where(function ($search) use ($query) {

                $search->where('title', 'like', $query . '%')
                    ->orWhere('artist', 'like', $query . '%')
                    ->orWhere('genre', 'like', $query . '%');

            })
            ->latest()
            ->limit(8)
            ->get([
                'title',
                'artist',
                'genre'
            ]);

        /*
        |--------------------------------------------------------------------------
        | Hilangkan data yang sama
        |--------------------------------------------------------------------------
        */

        $results = [];

        foreach ($songs as $song) {

            /*
            |--------------------------------------------------------------------------
            | Judul
            |--------------------------------------------------------------------------
            */

            if (
                $song->title &&
                stripos($song->title, $query) === 0
            ) {
                $key = 'title_' . strtolower($song->title);

                if (!isset($results[$key])) {

                    $results[$key] = [
                        'type' => 'song',
                        'text' => $song->title,
                        'subtext' => $song->artist,
                        'query' => $song->title
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Artist
            |--------------------------------------------------------------------------
            */

            if (
                $song->artist &&
                stripos($song->artist, $query) === 0
            ) {
                $key = 'artist_' . strtolower($song->artist);

                if (!isset($results[$key])) {

                    $results[$key] = [
                        'type' => 'artist',
                        'text' => $song->artist,
                        'subtext' => 'Artist',
                        'query' => $song->artist
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Genre
            |--------------------------------------------------------------------------
            */

            if (
                $song->genre &&
                stripos($song->genre, $query) === 0
            ) {
                $key = 'genre_' . strtolower($song->genre);

                if (!isset($results[$key])) {

                    $results[$key] = [
                        'type' => 'genre',
                        'text' => $song->genre,
                        'subtext' => 'Genre',
                        'query' => $song->genre
                    ];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Maksimal 8 suggestion
        |--------------------------------------------------------------------------
        */

        $results = array_values($results);

        $results = array_slice($results, 0, 8);

        return response()->json($results);
    }
}

