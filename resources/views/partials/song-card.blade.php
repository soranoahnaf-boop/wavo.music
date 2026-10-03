{{--
|--------------------------------------------------------------------------
| SONG CARD PARTIAL
|--------------------------------------------------------------------------
|
| Variable:
| - $song       : instance Song
| - $favorites  : array song_id yang difavorite user (opsional)
|
--}}

@php
    $favorites = $favorites ?? [];
    $isFavorited = in_array($song->id, $favorites);
@endphp

<div
    class="song-card"
    data-song-id="{{ $song->id }}"
    data-audio="{{ asset('storage/' . $song->audio_path) }}"
    data-title="{{ $song->title }}"
    data-artist="{{ $song->artist }}"
    data-cover="{{ $song->cover_path ? asset('storage/' . $song->cover_path) : '' }}"
>

    <div class="song-cover">

        @if($song->cover_path)
            <img src="{{ asset('storage/' . $song->cover_path) }}" alt="{{ $song->title }}">
        @else
            <div class="song-cover-placeholder">♫</div>
        @endif

        <div class="song-play-overlay"></div>

        @auth
            {{-- FAVORITE — star with water-fill animation --}}

            <button
                type="button"
                class="favorite-button {{ $isFavorited ? 'favorited' : '' }}"
                data-song-id="{{ $song->id }}"
                title="Favorite"
                aria-label="Favorite"
            >
                <span class="fav-star fav-star-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </span>
                <span class="fav-star fav-star-fill">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </span>
            </button>

            {{-- MORE — three dots --}}

            <button
                type="button"
                class="more-button"
                data-song-id="{{ $song->id }}"
                title="More options"
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="5" cy="12" r="2"></circle>
                    <circle cx="12" cy="12" r="2"></circle>
                    <circle cx="19" cy="12" r="2"></circle>
                </svg>
            </button>
        @endauth

    </div>

    <div class="song-title">{{ $song->title }}</div>
    <div class="song-artist">{{ $song->artist }}</div>

</div>