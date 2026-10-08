<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\HomePagexController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\SongController;
use App\Models\Song;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/home', [HomePagexController::class, 'index'])
    ->name('home');


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

Route::get('/search', [SearchController::class, 'index'])
    ->name('search');

Route::get('/search/suggest', [SearchController::class, 'suggest'])
    ->middleware('auth')
    ->name('search.suggest');


    Route::get('/radio', function () {

    $songs = Song::latest()->get();

    $songsData = $songs->map(function ($song) {

        return [
            'id' => $song->id,
            'title' => $song->title,
            'artist' => $song->artist,

            'audio' => $song->audio_path
                ? asset('storage/' . $song->audio_path)
                : null,

            'cover' => $song->cover_path
                ? asset('storage/' . $song->cover_path)
                : null,
        ];

    })->values();

    return view('radio', compact('songsData'));

})->name('radio');


/*
|--------------------------------------------------------------------------
| LYRICS (used by the full-screen player)
|--------------------------------------------------------------------------
*/

Route::get('/songs/{song}/lyrics', [SongController::class, 'lyrics'])
    ->whereNumber('song')
    ->name('songs.lyrics');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CREATOR
    |--------------------------------------------------------------------------
    */

    Route::delete('/creator/{song}', [CreatorController::class, 'destroy'])
    ->name('creator.destroy');

        /*
    |--------------------------------------------------------------------------
    | SHOW ALL — Songs & Artists
    |--------------------------------------------------------------------------
    */

    Route::get('/songs', [SongController::class, 'index'])
        ->name('songs.index');

    Route::get('/artists', [ArtistController::class, 'index'])
        ->name('artists.index');

    Route::get('/creator', [CreatorController::class, 'index'])
        ->name('creator');

    Route::post('/creator', [CreatorController::class, 'store'])
        ->name('creator.store');

    Route::delete('/creator/{song}', [CreatorController::class, 'destroy'])
        ->name('creator.destroy');


    /*
    |--------------------------------------------------------------------------
    | FAVORITE
    |--------------------------------------------------------------------------
    */

    Route::post('/home/{song}/favorite', [HomePagexController::class, 'favorite'])
        ->name('home.favorite');


    /*
    |--------------------------------------------------------------------------
    | RECENTLY PLAYED
    |--------------------------------------------------------------------------
    */

    Route::post('/home/{song}/played', [HomePagexController::class, 'played'])
        ->name('home.played');


    /*
    |--------------------------------------------------------------------------
    | PLAYLIST
    |--------------------------------------------------------------------------
    */

    Route::get('/playlists', [PlaylistController::class, 'index'])
        ->name('playlist.index');

    Route::get('/playlists/mine', [PlaylistController::class, 'mine'])
        ->name('playlist.mine');

    Route::post('/playlist', [PlaylistController::class, 'store'])
        ->name('playlist.store');

    Route::get('/playlist/{playlist}', [PlaylistController::class, 'show'])
        ->name('playlist.show');

    Route::delete('/playlist/{playlist}', [PlaylistController::class, 'destroy'])
        ->name('playlist.destroy');

    Route::post('/playlist/{playlist}/cover', [PlaylistController::class, 'uploadCover'])
        ->name('playlist.uploadCover');

    Route::post('/playlist/{playlist}/song/{song}', [PlaylistController::class, 'addSong'])
        ->name('playlist.addSong');

    Route::delete('/playlist/{playlist}/song/{song}', [PlaylistController::class, 'removeSong'])
        ->name('playlist.removeSong');


    /*
    |--------------------------------------------------------------------------
    | FAVORITES PAGE
    |--------------------------------------------------------------------------
    */

    Route::get('/favorites', [PlaylistController::class, 'favorites'])
        ->name('favorites');


       /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    // Halaman profile (display)
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    // Halaman edit profile (settings)
    Route::get('/settings', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo.update');

    Route::patch('/settings', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/settings', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('home');
})
    ->middleware('auth')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| BREEZE AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';