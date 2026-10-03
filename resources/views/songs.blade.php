<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title }} - Wavo Music</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://unpkg.com/@hotwired/turbo@8.0.0/dist/turbo.es2017-umd.js"></script>

    <!-- =====================================================
     PAGE TRANSITION — Animasi pindah halaman
     ===================================================== -->
<style>
    /* Fallback: fade opacity (semua browser) */
    body {
        opacity: 1;
        transition: opacity 0.25s ease;
    }
    body.page-exit {
        opacity: 0;
    }

    /* View Transitions API (browser modern) */
    @view-transition {
        navigation: auto;
    }

    ::view-transition-old(root) {
        animation: 250ms cubic-bezier(0.4, 0, 0.2, 1) both fade-out;
    }
    ::view-transition-new(root) {
        animation: 350ms cubic-bezier(0.4, 0, 0.2, 1) both fade-in;
    }

    @keyframes fade-out {
        to { opacity: 0; transform: scale(0.98); }
    }
    @keyframes fade-in {
        from { opacity: 0; transform: scale(1.02); }
    }
</style>

<script>
    (function() {
        document.addEventListener('turbo:before-render', (event) => {
            if (!document.startViewTransition) return;
            event.preventDefault();
            document.startViewTransition(() => {
                event.detail.resume();
            });
        });

        document.addEventListener('turbo:before-visit', () => {
            if (document.startViewTransition) return;
            document.body.classList.add('page-exit');
        });

        document.addEventListener('turbo:load', () => {
            document.body.classList.remove('page-exit');
            document.body.style.opacity = '1';
        });
    })();
</script>
<!-- =====================================================
     END PAGE TRANSITION
     ===================================================== -->

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { min-height: 100%; }
        body { background: #303030; color: #f5f5f5; font-family: Arial, Helvetica, sans-serif; }
        a { color: inherit; text-decoration: none; }
        button { border: none; background: none; color: inherit; cursor: pointer; font-family: inherit; }

        .layout { display: flex; align-items: flex-start; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            position: sticky; top: 17px; margin: 17px 0 0 24px;
            width: 179px; height: calc(100vh - 34px); max-height: 640px; flex-shrink: 0;
            border: 1px solid #555; border-radius: 13px; background: #303030;
            z-index: 100; padding: 20px 10px 15px;
            display: flex; flex-direction: column; overflow-y: auto;
        }
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #444; border-radius: 10px; }

        .brand { display: flex; align-items: center; gap: 10px; padding: 0 8px; margin-bottom: 24px; flex-shrink: 0; }
        .brand-logo { color: #c5a45c; font-size: 25px; line-height: 1; }
        .brand-text { color: #f5f5f5; font-size: 17px; font-weight: 600; }

        .sidebar-nav { display: flex; flex-direction: column; gap: 2px; }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            height: 34px; padding: 0 12px; box-sizing: border-box;
            color: #b8b8b8; border-left: 3px solid transparent; border-radius: 6px;
            font-size: 12px; font-weight: 600; transition: background .15s, color .15s;
        }
        .nav-link:hover { background: #383838; color: #f5f5f5; }
        .nav-link.active { background: #383838; color: #f5f5f5; border-left-color: #c5a45c; }
        .nav-icon { width: 16px; text-align: center; font-size: 15px; line-height: 1; color: inherit; }
        .nav-link.active .nav-icon { color: #c5a45c; }

        .library-title { font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 0 12px; margin: 20px 0 8px; }
        .library-item { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; color: #b8b8b8; transition: background .15s, color .15s; }
        .library-item:hover { background: #383838; color: #f5f5f5; }
        .library-thumb { width: 26px; height: 26px; border-radius: 6px; background: #4a4a4a; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #c5a45c; overflow: hidden; }
        .library-thumb.round { border-radius: 50%; }

        .profile { display: flex; align-items: center; gap: 10px; padding: 10px 6px 0; border-top: 1px solid #3d3d3d; margin-top: auto; flex-shrink: 0; }
        .profile-avatar { width: 31px; height: 31px; border-radius: 50%; background: #111; display: flex; align-items: center; justify-content: center; color: #c5a45c; font-size: 12px; font-weight: bold; flex-shrink: 0; border: 1px solid #444; overflow: hidden; }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-name { color: #f5f5f5; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* MAIN */
        .main { flex: 1; min-width: 0; padding: 24px 38px 130px 25px; }
        .content { width: 100%; max-width: 1180px; }

        /* BACK BUTTON */
        .back-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 16px;
            background: #3d3d3d;
            border-radius: 20px;
            color: #b8b8b8;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 24px;
            transition: background .15s, color .15s;
        }
        .back-btn:hover { background: #4a4a4a; color: #fff; }

        /* HEADER */
        .page-header { margin-bottom: 30px; }
        .page-title { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 6px; }
        .page-subtitle { font-size: 13px; color: #999; }

        /* FILTER TABS */
        .filter-tabs { display: flex; gap: 8px; margin-bottom: 24px; }
        .filter-tab {
            padding: 8px 20px;
            border-radius: 20px;
            background: #3d3d3d;
            color: #b8b8b8;
            font-size: 12px;
            font-weight: 600;
            transition: background .15s, color .15s;
        }
        .filter-tab:hover { background: #4a4a4a; color: #f5f5f5; }
        .filter-tab.active { background: #c5a45c; color: #1a1a1a; }

        /* SONG GRID */
        .song-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .song-card {
            position: relative;
            cursor: pointer;
            min-width: 0;
        }

        .song-cover {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 10px;
            background: #4a4a4a;
            overflow: hidden;
            margin-bottom: 10px;
            transition: transform .15s;
        }

        .song-card:hover .song-cover { transform: scale(1.02); }

        .song-cover img {
            width: 100%; height: 100%; object-fit: cover; display: block;
            pointer-events: none;
        }

        .song-cover-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: #888; font-size: 42px;
        }

        .song-play-overlay {
            position: absolute; inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity .15s;
            pointer-events: none;
        }

        .song-card:hover .song-play-overlay { opacity: 1; }

        .song-play-overlay::after {
            content: '';
            width: 44px; height: 44px; border-radius: 50%;
            background: #c5a45c;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231a1a1a'><polygon points='8 5 19 12 8 19 8 5'/></svg>");
            background-size: 20px;
            background-position: center;
            background-repeat: no-repeat;
        }

        .song-title {
            font-size: 13px; font-weight: 600; color: #f5f5f5;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 4px;
        }

        .song-artist {
            font-size: 11px; color: #999;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* FAVORITE BUTTON */
        .favorite-button {
            position: absolute; top: 8px; right: 8px;
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(20, 20, 20, 0.75);
            display: flex; align-items: center; justify-content: center;
            z-index: 7; overflow: hidden;
            transition: background .2s, transform .15s;
            color: #4a4a4a;
        }
        .favorite-button:hover { background: rgba(30, 30, 30, 0.95); transform: scale(1.08); }

        .fav-star { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; }
        .fav-star-outline { color: #6a6a6a; transition: color .2s; }
        .favorite-button:hover .fav-star-outline { color: #8a8a8a; }

        .fav-star-fill {
            color: #facc15;
            clip-path: inset(100% 0 0 0);
            transition: clip-path .5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .favorite-button.favorited .fav-star-fill { clip-path: inset(0 0 0 0); }
        .favorite-button.favorited { background: rgba(250, 204, 21, 0.15); }
        .favorite-button.favorited .fav-star-outline { color: #facc15; }

        /* EMPTY */
        .empty {
            padding: 60px 30px;
            background: #363636;
            border: 1px solid #484848;
            border-radius: 12px;
            color: #999;
            text-align: center;
            font-size: 14px;
        }

        /* MUSIC PLAYER */
        .music-player {
            position: fixed; left: 50%; bottom: 24px; transform: translateX(-50%);
            width: 620px; max-width: calc(100% - 48px); height: 68px;
            background: #2a2a2a; border: 1px solid #555; border-radius: 34px;
            z-index: 2000; display: flex; align-items: center;
            padding: 0 22px; gap: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .player-info { display: flex; align-items: center; gap: 10px; width: 160px; flex-shrink: 0; }
        .player-cover { width: 38px; height: 38px; border-radius: 8px; background: #1a1a1a; display: flex; align-items: center; justify-content: center; color: #c5a45c; font-size: 18px; flex-shrink: 0; border: 1px solid #3d3d3d; overflow: hidden; }
        .player-cover img { width: 100%; height: 100%; object-fit: cover; }
        .player-info-text { min-width: 0; flex: 1; }
        .player-title { color: #f5f5f5; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .player-artist { color: #888; font-size: 10px; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .player-controls { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; min-width: 0; }
        .player-buttons { display: flex; align-items: center; gap: 16px; }
        .player-buttons button { display: flex; align-items: center; padding: 4px; color: #888; transition: color .15s; }
        .player-buttons button:hover { color: #f5f5f5; }
        .player-buttons button.play { background: #c5a45c; color: #1a1a1a; width: 34px; height: 34px; border-radius: 50%; justify-content: center; }
        .player-buttons button.play:hover { transform: scale(1.08); background: #d4b76a; }
        .player-buttons button.active { color: #c5a45c; }

        .player-progress { display: flex; align-items: center; gap: 8px; width: 100%; max-width: 320px; }
        .player-progress span { color: #777; font-size: 10px; flex-shrink: 0; font-variant-numeric: tabular-nums; }
        .progress-bar { flex: 1; height: 3px; background: #444; border-radius: 2px; overflow: hidden; cursor: pointer; }
        .progress-fill { height: 100%; background: #c5a45c; width: 0%; border-radius: 2px; transition: width .1s linear; }

        .player-right { display: flex; align-items: center; gap: 16px; width: 90px; justify-content: flex-end; flex-shrink: 0; }
        .player-right button { display: flex; align-items: center; padding: 4px; color: #f5f5f5; transition: color .15s; }
        .player-right button:hover { color: #c5a45c; }

        /* RESPONSIVE */
        @media (max-width: 1100px) { .song-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 700px) {
            .layout { flex-direction: column; }
            .sidebar { position: relative; top: auto; margin: 15px; width: calc(100% - 30px); height: auto; max-height: none; }
            .main { padding: 15px 15px 130px; }
            .song-grid { grid-template-columns: repeat(2, 1fr); }
            .music-player { width: calc(100% - 30px); padding: 0 15px; gap: 10px; }
            .player-info { display: none; }
        }
    </style>
</head>

<body>

<div class="layout">

    {{-- SIDEBAR --}}

    <aside class="sidebar" id="sidebar" data-turbo-permanent>

        <a href="{{ route('home') }}" class="brand">
            <span class="brand-logo">〽</span>
            <span class="brand-text">Music</span>
        </a>

        <nav class="sidebar-nav">
            <a href="{{ route('search') }}" class="nav-link">
                <span class="nav-icon">⌕</span><span>Search</span>
            </a>
            <a href="{{ route('home') }}" class="nav-link">
                <span class="nav-icon">⌂</span><span>Home</span>
            </a>
            <a href="{{ route('creator') }}" class="nav-link">
                <span class="nav-icon">▦</span><span>Creator</span>
            </a>
            <a href="#" class="nav-link">
                <span class="nav-icon">◉</span><span>Radio</span>
            </a>
        </nav>

        @auth
            <div class="library-title">Library</div>
            <a href="{{ route('playlist.index') }}" class="library-item">
                <div class="library-thumb">▶</div>
                <span>Hot Play</span>
            </a>
            <a href="{{ route('favorites') }}" class="library-item">
                <div class="library-thumb round">♥</div>
                <span>Favourite</span>
            </a>
        @endauth

        <a href="{{ route('profile.edit') }}" class="profile">
            <div class="profile-avatar">
                @if(Auth::user()->profile_photo_path ?? false)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>
            <div class="profile-name">{{ Auth::user()->name }}</div>
        </a>

    </aside>


    {{-- MAIN --}}

    <main class="main">
        <div class="content">

            {{-- Back Button --}}
            <a href="{{ route('home') }}" class="back-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Back to Home
            </a>


            {{-- Header --}}
            <div class="page-header">
                <h1 class="page-title">{{ $title }}</h1>
                @if($subtitle)
                    <p class="page-subtitle">{{ $subtitle }}</p>
                @endif
            </div>


            {{-- Filter Tabs --}}
            <div class="filter-tabs">
                <a href="{{ route('songs.index', ['filter' => 'all']) }}"
                   class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
                    All Music
                </a>
                <a href="{{ route('songs.index', ['filter' => 'recommended']) }}"
                   class="filter-tab {{ $filter === 'recommended' ? 'active' : '' }}">
                    Recommended
                </a>
                <a href="{{ route('songs.index', ['filter' => 'recent']) }}"
                   class="filter-tab {{ $filter === 'recent' ? 'active' : '' }}">
                    Recently Played
                </a>
            </div>


            {{-- Song Grid --}}
            @if($songs->count() > 0)

                <div class="song-grid">

                    @foreach($songs as $song)

                        @php
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

                                <button
                                    type="button"
                                    class="favorite-button {{ $isFavorited ? 'favorited' : '' }}"
                                    data-song-id="{{ $song->id }}"
                                    title="Favorite"
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

                            </div>

                            <div class="song-title">{{ $song->title }}</div>
                            <div class="song-artist">{{ $song->artist }}</div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty">
                    Belum ada lagu di sini.
                </div>

            @endif

        </div>
    </main>

</div>



{{-- MUSIC PLAYER --}}

<div class="music-player" id="musicPlayer" data-turbo-permanent>

    <div class="player-info">
        <div class="player-cover" id="playerCover">〽</div>
        <div class="player-info-text">
            <div class="player-title" id="playerTitle">Not Playing</div>
            <div class="player-artist" id="playerArtist">Select a song</div>
        </div>
    </div>

    <div class="player-controls">
        <div class="player-buttons">

            <button type="button" id="shuffleBtn" title="Shuffle">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 3 21 3 21 8"></polyline>
                    <line x1="4" y1="20" x2="21" y2="3"></line>
                    <polyline points="21 16 21 21 16 21"></polyline>
                    <line x1="15" y1="15" x2="21" y2="21"></line>
                    <line x1="4" y1="4" x2="9" y2="9"></line>
                </svg>
            </button>

            <button type="button" id="prevBtn" title="Previous">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="19 20 9 12 19 4 19 20"></polygon>
                    <line x1="5" y1="19" x2="5" y2="5" stroke="currentColor" stroke-width="2"></line>
                </svg>
            </button>

            <button type="button" class="play" id="playPauseBtn" title="Play">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <polygon id="playIcon" points="6 4 20 12 6 20 6 4"></polygon>
                </svg>
            </button>

            <button type="button" id="nextBtn" title="Next">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="5 4 15 12 5 20 5 4"></polygon>
                    <line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2"></line>
                </svg>
            </button>

            <button type="button" id="repeatBtn" title="Repeat">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="17 1 21 5 17 9"></polyline>
                    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                    <polyline points="7 23 3 19 7 15"></polyline>
                    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                </svg>
            </button>

        </div>

        <div class="player-progress">
            <span id="currentTime">0:00</span>
            <div class="progress-bar" id="progressBar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <span id="totalTime">0:00</span>
        </div>
    </div>

    <div class="player-right">
        <button type="button" id="muteBtn" title="Mute">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" fill="currentColor"></polygon>
                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
            </svg>
        </button>
    </div>

</div>



{{-- GLOBAL AUDIO --}}

<audio id="globalAudio" preload="none" data-turbo-permanent></audio>



<script>

    (function() {

        if (window.__songsPageInited) return;
        window.__songsPageInited = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        const globalAudio = document.getElementById('globalAudio');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const playIcon = document.getElementById('playIcon');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const shuffleBtn = document.getElementById('shuffleBtn');
        const repeatBtn = document.getElementById('repeatBtn');
        const muteBtn = document.getElementById('muteBtn');
        const playerTitle = document.getElementById('playerTitle');
        const playerArtist = document.getElementById('playerArtist');
        const playerCover = document.getElementById('playerCover');
        const progressBar = document.getElementById('progressBar');
        const progressFill = document.getElementById('progressFill');
        const currentTimeEl = document.getElementById('currentTime');
        const totalTimeEl = document.getElementById('totalTime');

        let songCards = [];
        let currentIndex = -1;
        let shuffle = false;
        let repeat = false;
        let muted = false;

        function refreshSongCards() {
            songCards = Array.from(document.querySelectorAll('.song-card'));
        }

        function formatTime(seconds) {
            if (!seconds || isNaN(seconds)) return '0:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return m + ':' + (s < 10 ? '0' : '') + s;
        }

        function playSong(index, autoPlay = true) {
            if (index < 0 || index >= songCards.length) return;
            currentIndex = index;
            const card = songCards[index];

            playerTitle.textContent = card.dataset.title || 'Unknown';
            playerArtist.textContent = card.dataset.artist || 'Unknown';

            const cover = card.dataset.cover || '';
            playerCover.innerHTML = cover ? `<img src="${cover}" alt="">` : '〽';

            globalAudio.src = card.dataset.audio;
            globalAudio.load();
            if (autoPlay) globalAudio.play().catch(() => {});

            if (csrfToken && card.dataset.songId) {
                fetch('{{ url('/home') }}/' + card.dataset.songId + '/played', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                }).catch(() => {});
            }
        }

        playPauseBtn.addEventListener('click', () => {
            if (currentIndex === -1) {
                if (songCards.length > 0) playSong(0);
                return;
            }
            if (globalAudio.paused) globalAudio.play();
            else globalAudio.pause();
        });

        globalAudio.addEventListener('play', () => playIcon.setAttribute('points', '6 4 14 4 14 20 6 20'));
        globalAudio.addEventListener('pause', () => playIcon.setAttribute('points', '6 4 20 12 6 20 6 4'));

        function playNext() {
            if (songCards.length === 0) return;
            let nextIndex;
            if (shuffle) {
                if (songCards.length === 1) nextIndex = 0;
                else { do { nextIndex = Math.floor(Math.random() * songCards.length); } while (nextIndex === currentIndex); }
            } else {
                nextIndex = currentIndex + 1;
                if (nextIndex >= songCards.length) nextIndex = 0;
            }
            playSong(nextIndex);
        }

        nextBtn.addEventListener('click', playNext);
        prevBtn.addEventListener('click', () => {
            if (songCards.length === 0) return;
            let prevIndex = currentIndex - 1;
            if (prevIndex < 0) prevIndex = songCards.length - 1;
            playSong(prevIndex);
        });

        globalAudio.addEventListener('ended', () => {
            if (repeat) { globalAudio.currentTime = 0; globalAudio.play(); return; }
            playNext();
        });

        shuffleBtn.addEventListener('click', () => { shuffle = !shuffle; shuffleBtn.classList.toggle('active', shuffle); });
        repeatBtn.addEventListener('click', () => { repeat = !repeat; repeatBtn.classList.toggle('active', repeat); });

        globalAudio.addEventListener('timeupdate', () => {
            if (!globalAudio.duration) return;
            progressFill.style.width = ((globalAudio.currentTime / globalAudio.duration) * 100) + '%';
            currentTimeEl.textContent = formatTime(globalAudio.currentTime);
        });
        globalAudio.addEventListener('loadedmetadata', () => totalTimeEl.textContent = formatTime(globalAudio.duration));

        progressBar.addEventListener('click', (e) => {
            if (!globalAudio.duration) return;
            const rect = progressBar.getBoundingClientRect();
            globalAudio.currentTime = ((e.clientX - rect.left) / rect.width) * globalAudio.duration;
        });

        muteBtn.addEventListener('click', () => { muted = !muted; globalAudio.muted = muted; muteBtn.style.color = muted ? '#d9534f' : ''; });

        // Klik card → play
        document.addEventListener('click', (e) => {
            const card = e.target.closest('.song-card');
            if (!card) return;
            if (e.target.closest('.favorite-button')) return;

            refreshSongCards();
            const index = songCards.indexOf(card);
            if (index !== -1) playSong(index);
        });

        // Favorite toggle
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.favorite-button');
            if (!btn) return;

            e.stopPropagation();
            e.preventDefault();

            const songId = btn.dataset.songId;
            const wasFavorited = btn.classList.contains('favorited');

            document.querySelectorAll('.favorite-button[data-song-id="' + songId + '"]').forEach(item => {
                if (wasFavorited) item.classList.remove('favorited');
                else item.classList.add('favorited');
            });

            fetch('{{ url('/home') }}/' + songId + '/favorite', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    document.querySelectorAll('.favorite-button[data-song-id="' + songId + '"]').forEach(item => {
                        if (wasFavorited) item.classList.add('favorited');
                        else item.classList.remove('favorited');
                    });
                }
            })
            .catch(() => {});
        });

        refreshSongCards();

        document.addEventListener('turbo:load', () => {
            refreshSongCards();
        });

    })();

</script>

</body>
</html>