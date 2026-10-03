<?php

namespace App\Http\Controllers;

use App\Models\Song;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Song::select('artist')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('artist')
            ->orderByDesc('total')
            ->get();

        return view('artists', compact('artists'));
    }
}