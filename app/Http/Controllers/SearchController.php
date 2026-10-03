<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Daftar genre yang tersedia di Wavo.
     */
    private function genres(): array
    {
        return [
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
            'Other',
        ];
    }


    /**
     * Search page.
     */
    public function index(Request $request): View
    {
        $query = trim($request->input('q', ''));

        $songs = collect();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        |
        | Search berdasarkan:
        | - Judul lagu
        | - Artist
        | - Genre
        |
        | Kalau query sama persis dengan salah satu genre,
        | maka hasil difilter berdasarkan genre tersebut.
        |
        */

        if ($query !== '') {

            $isGenre = in_array(
                $query,
                $this->genres(),
                true
            );


            /*
            |--------------------------------------------------------------------------
            | GENRE SEARCH
            |--------------------------------------------------------------------------
            */

            if ($isGenre) {

                $songs = Song::query()
                    ->where('genre', $query)
                    ->latest()
                    ->get();

            }


            /*
            |--------------------------------------------------------------------------
            | NORMAL SEARCH
            |--------------------------------------------------------------------------
            */

            else {

                $songs = Song::query()
                    ->where(function ($search) use ($query) {

                        $search
                            ->where('title', 'like', '%' . $query . '%')
                            ->orWhere('artist', 'like', '%' . $query . '%')
                            ->orWhere('genre', 'like', '%' . $query . '%');

                    })
                    ->latest()
                    ->get();

            }
        }


        return view('search', [
            'songs' => $songs,
            'query' => $query,
            'genres' => $this->genres(),
        ]);
    }


    /**
     * Search suggestions / autocomplete.
     */
    public function suggest(Request $request)
    {
        $query = trim($request->input('q', ''));

        if ($query === '') {
            return response()->json([]);
        }


        /*
        |--------------------------------------------------------------------------
        | CARI DATA
        |--------------------------------------------------------------------------
        */

        $songs = Song::query()
            ->where(function ($search) use ($query) {

                $search
                    ->where('title', 'like', '%' . $query . '%')
                    ->orWhere('artist', 'like', '%' . $query . '%')
                    ->orWhere('genre', 'like', '%' . $query . '%');

            })
            ->latest()
            ->limit(20)
            ->get([
                'title',
                'artist',
                'genre',
            ]);


        $results = [];


        /*
        |--------------------------------------------------------------------------
        | BUAT SUGGESTION
        |--------------------------------------------------------------------------
        */

        foreach ($songs as $song) {

            /*
            |--------------------------------------------------------------------------
            | TITLE
            |--------------------------------------------------------------------------
            */

            if ($song->title) {

                $key = 'song_' . strtolower($song->title);

                if (!isset($results[$key])) {

                    $results[$key] = [
                        'type' => 'song',
                        'text' => $song->title,
                        'subtext' => $song->artist ?: 'Song',
                        'query' => $song->title,
                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | ARTIST
            |--------------------------------------------------------------------------
            */

            if ($song->artist) {

                $key = 'artist_' . strtolower($song->artist);

                if (!isset($results[$key])) {

                    $results[$key] = [
                        'type' => 'artist',
                        'text' => $song->artist,
                        'subtext' => 'Artist',
                        'query' => $song->artist,
                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | GENRE
            |--------------------------------------------------------------------------
            */

            if ($song->genre) {

                $key = 'genre_' . strtolower($song->genre);

                if (!isset($results[$key])) {

                    $results[$key] = [
                        'type' => 'genre',
                        'text' => $song->genre,
                        'subtext' => 'Genre',
                        'query' => $song->genre,
                    ];
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TAMBAHKAN GENRE BOX KE SUGGESTION
        |--------------------------------------------------------------------------
        */

        foreach ($this->genres() as $genre) {

            if (stripos($genre, $query) !== false) {

                $key = 'genre_' . strtolower($genre);

                if (!isset($results[$key])) {

                    $results[$key] = [
                        'type' => 'genre',
                        'text' => $genre,
                        'subtext' => 'Genre',
                        'query' => $genre,
                    ];
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | BATASI HASIL
        |--------------------------------------------------------------------------
        */

        $results = array_values($results);

        $results = array_slice($results, 0, 8);


        return response()->json($results);
    }
}