<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Wavo Music</title>

    <script src="https://unpkg.com/@hotwired/turbo@8.0.0/dist/turbo.es2017-umd.js"></script>

    <style>
        body { opacity: 1; transition: opacity 0.25s ease; }
        body.page-exit { opacity: 0; }
        @view-transition { navigation: auto; }
        ::view-transition-old(root) { animation: 250ms cubic-bezier(0.4, 0, 0.2, 1) both fade-out; }
        ::view-transition-new(root) { animation: 350ms cubic-bezier(0.4, 0, 0.2, 1) both fade-in; }
        @keyframes fade-out { to { opacity: 0; transform: scale(0.98); } }
        @keyframes fade-in { from { opacity: 0; transform: scale(1.02); } }
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
            text-decoration: none;
            color: #b8b8b8; background: transparent;
            border-left: 3px solid transparent; border-radius: 6px;
            font-size: 12px; font-weight: 600;
            transition: background .15s, color .15s;
        }
        .nav-link:hover { background: #383838; color: #f5f5f5; }
        .nav-link.active { background: #383838; color: #f5f5f5; border-left-color: #c5a45c; }
        .nav-icon { width: 16px; text-align: center; font-size: 15px; line-height: 1; color: inherit; }
        .nav-link.active .nav-icon { color: #c5a45c; }

        .library-title {
            font-size: 11px; color: #888; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px;
            padding: 0 12px; margin: 20px 0 8px;
        }
        .library-item {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 12px; border-radius: 6px;
            font-size: 12px; font-weight: 500;
            color: #b8b8b8; transition: background .15s, color .15s;
        }
        .library-item:hover { background: #383838; color: #f5f5f5; }
        .library-item.active { background: #383838; color: #f5f5f5; }
        .library-thumb {
            width: 26px; height: 26px; border-radius: 6px;
            background: #4a4a4a; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; color: #c5a45c; overflow: hidden;
        }
        .library-thumb.round { border-radius: 50%; }

        .profile {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 6px 0; border-top: 1px solid #3d3d3d;
            margin-top: auto; flex-shrink: 0;
        }
        .profile-avatar {
            width: 31px; height: 31px; border-radius: 50%; background: #111;
            display: flex; align-items: center; justify-content: center;
            color: #c5a45c; font-size: 12px; font-weight: bold;
            flex-shrink: 0; border: 1px solid #444; overflow: hidden;
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-name {
            color: #f5f5f5; font-size: 12px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* MAIN */
        .main { flex: 1; min-width: 0; padding: 24px 38px 130px 25px; }
        .content { width: 100%; max-width: 1180px; }

        /* SEARCH BAR */
        .search-wrapper {
            position: relative;
            width: 550px;
            margin-bottom: 30px;
        }

        .search-form {
            width: 550px; height: 40px;
            border: 1px solid #505050; border-radius: 20px;
            display: flex; align-items: center;
            padding: 0 16px; box-sizing: border-box;
            background: #383838;
        }

        .search-form input {
            width: 100%; border: none; outline: none; background: transparent;
            color: #fff; font-size: 14px; font-weight: 500;
        }

        .search-form input::placeholder { color: #777; }

        .search-suggestions {
            position: absolute; top: 47px; left: 0;
            width: 550px; background: #383838;
            border: 1px solid #505050; border-radius: 12px;
            overflow: hidden; z-index: 3000; display: none;
            box-sizing: border-box;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
        }

        .search-suggestions.show { display: block; }

        .suggestion-item {
            width: 100%; min-height: 52px;
            display: flex; align-items: center; gap: 12px;
            padding: 8px 14px; box-sizing: border-box;
            border: none; background: transparent; color: #fff;
            cursor: pointer; text-align: left;
        }

        .suggestion-item:hover { background: #454545; }

        .suggestion-icon {
            width: 32px; height: 32px; border-radius: 7px; background: #292929;
            display: flex; align-items: center; justify-content: center;
            color: #c5a45c; font-size: 16px; flex-shrink: 0;
        }

        .suggestion-text { min-width: 0; flex: 1; }
        .suggestion-title {
            color: #f5f5f5; font-size: 13px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .suggestion-subtitle { color: #888; font-size: 10px; margin-top: 4px; }
        .suggestion-empty { padding: 16px; color: #888; font-size: 12px; text-align: center; }

        /* PAGE TITLE */
        .page-title { font-size: 22px; font-weight: 700; margin-bottom: 24px; color: #fff; }

        /* GENRE GRID */
        .genre-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .genre-card {
            position: relative;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            padding: 16px;
            box-sizing: border-box;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            transition: transform .15s, filter .15s;
            cursor: pointer;
        }

        .genre-card:hover {
            transform: translateY(-3px);
            filter: brightness(1.15);
        }

        .genre-card::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.15) 0%, transparent 50%),
                radial-gradient(circle at 20% 80%, rgba(0,0,0,0.15) 0%, transparent 50%);
            z-index: 0;
        }

        .genre-card::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.5) 100%);
            z-index: 1;
        }

        .genre-name {
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }

        /* SEARCH RESULTS */
        .song-result {
            width: 100%;
            min-height: 75px;
            background: #383838;
            border: 1px solid #444;
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            box-sizing: border-box;
            gap: 14px;
            margin-bottom: 10px;
        }

        .song-result-cover {
            width: 55px; height: 55px; border-radius: 7px;
            overflow: hidden; background: #555; flex-shrink: 0;
        }
        .song-result-cover img { width: 100%; height: 100%; object-fit: cover; }

        .song-result-cover-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: #888; font-size: 20px;
        }

        .song-result-info { flex: 1; min-width: 0; }
        .song-result-title {
            color: #fff; font-size: 14px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .song-result-artist { color: #aaa; font-size: 11px; margin-top: 5px; }
        .song-result-genre { color: #777; font-size: 10px; margin-top: 3px; }

        /* EMPTY */
        .empty {
            padding: 60px 30px; background: #363636;
            border: 1px solid #484848; border-radius: 12px;
            color: #999; text-align: center; font-size: 14px;
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
        .player-cover {
            width: 38px; height: 38px; border-radius: 8px; background: #1a1a1a;
            display: flex; align-items: center; justify-content: center;
            color: #c5a45c; font-size: 18px; flex-shrink: 0;
            border: 1px solid #3d3d3d; overflow: hidden;
        }
        .player-cover img { width: 100%; height: 100%; object-fit: cover; }
        .player-info-text { min-width: 0; flex: 1; }
        .player-title {
            color: #f5f5f5; font-size: 12px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .player-artist {
            color: #888; font-size: 10px; margin-top: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .player-controls { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; min-width: 0; }
        .player-buttons { display: flex; align-items: center; gap: 16px; }
        .player-buttons button { display: flex; align-items: center; padding: 4px; color: #888; transition: color .15s; }
        .player-buttons button:hover { color: #f5f5f5; }
        .player-buttons button.play {
            background: #c5a45c; color: #1a1a1a; width: 34px; height: 34px;
            border-radius: 50%; justify-content: center;
            transition: transform .15s, background .15s;
        }
        .player-buttons button.play:hover { transform: scale(1.08); background: #d4b76a; }
        .player-buttons button.active { color: #c5a45c; }
        .player-progress { display: flex; align-items: center; gap: 8px; width: 100%; max-width: 320px; }
        .player-progress span { color: #777; font-size: 10px; flex-shrink: 0; font-variant-numeric: tabular-nums; }
        .progress-bar { flex: 1; height: 3px; background: #444; border-radius: 2px; overflow: hidden; cursor: pointer; }
        .progress-fill { height: 100%; background: #c5a45c; width: 0%; border-radius: 2px; transition: width .1s linear; }
        .player-right { display: flex; align-items: center; gap: 16px; width: 90px; justify-content: flex-end; flex-shrink: 0; }
        .player-right button { display: flex; align-items: center; padding: 4px; color: #f5f5f5; transition: color .15s; }
        .player-right button:hover { color: #c5a45c; }

        @media (max-width: 1100px) { .genre-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 900px) { .genre-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 700px) {
            .layout { flex-direction: column; }
            .sidebar { position: relative; top: auto; margin: 15px; width: calc(100% - 30px); height: auto; max-height: none; }
            .main { padding: 15px 15px 130px; }
            .genre-grid { grid-template-columns: repeat(2, 1fr); }
            .search-wrapper, .search-form, .search-suggestions { width: 100%; }
            .music-player { width: calc(100% - 30px); padding: 0 15px; gap: 10px; }
            .player-info { display: none; }
        }
    </style>
</head>

<body>

<div class="layout">

    {{-- SIDEBAR --}}
    @include('partials.sidebar')


    {{-- MAIN --}}

    <main class="main">
        <div class="content">

            {{-- SEARCH BAR --}}
            <div class="search-wrapper">

                <form id="searchForm" class="search-form" action="{{ route('search') }}" method="GET">
                    <span style="color: #777; font-size: 20px; line-height: 1; margin-right: 10px;">⌕</span>
                    <input
                        id="searchInput"
                        type="text"
                        name="q"
                        value="{{ $query ?? '' }}"
                        placeholder="Search"
                        autocomplete="off"
                    >
                </form>

                <div id="searchSuggestions" class="search-suggestions"></div>

            </div>


            {{-- SEARCH RESULTS --}}
            @if(!empty($query))

                <h1 class="page-title">Search Results</h1>

                @if($songs->count() > 0)

                    @foreach($songs as $song)

                        <div class="song-result">

                            <div class="song-result-cover">
                                @if($song->cover_path)
                                    <img src="{{ asset('storage/' . $song->cover_path) }}" alt="{{ $song->title }}">
                                @else
                                    <div class="song-result-cover-placeholder">〽</div>
                                @endif
                            </div>

                            <div class="song-result-info">
                                <div class="song-result-title">{{ $song->title }}</div>
                                <div class="song-result-artist">{{ $song->artist }}</div>
                                <div class="song-result-genre">{{ $song->genre }}</div>
                            </div>

                            <audio controls preload="none" style="width: 230px;">
                                <source src="{{ asset('storage/' . $song->audio_path) }}">
                            </audio>

                        </div>

                    @endforeach

                @else

                    <div class="empty">
                        No music found for "<span style="color:#c5a45c;">{{ $query }}</span>"
                    </div>

                @endif


            @else

                {{-- BROWSE CATEGORIES --}}
                <h1 class="page-title">Browse Categories</h1>

                @php
                    $genres = [
                        'Pop' => ['#FF6B9D', '#C44569'],
                        'Rock' => ['#8B0000', '#2C2C2C'],
                        'Hip Hop' => ['#F39C12', '#D35400'],
                        'Rap' => ['#2C3E50', '#1A252F'],
                        'R&B' => ['#8E44AD', '#5B2C6F'],
                        'Soul' => ['#D35400', '#6E2C00'],
                        'Funk' => ['#F1C40F', '#B7950B'],
                        'Jazz' => ['#6B4423', '#2C1F0F'],
                        'Blues' => ['#2980B9', '#154360'],
                        'Classical' => ['#7F8C8D', '#424949'],
                        'Country' => ['#A0522D', '#5D3A1A'],
                        'Folk' => ['#7CB342', '#33691E'],
                        'Reggae' => ['#27AE60', '#145A32'],
                        'Gospel' => ['#E67E22', '#935116'],
                        'Electronic' => ['#00BCD4', '#006064'],
                        'EDM' => ['#9C27B0', '#4A148C'],
                        'House' => ['#3F51B5', '#1A237E'],
                        'Techno' => ['#607D8B', '#263238'],
                        'Trance' => ['#673AB7', '#311B92'],
                        'Dubstep' => ['#E91E63', '#880E4F'],
                        'Drum & Bass' => ['#FF5722', '#BF360C'],
                        'Ambient' => ['#B0BEC5', '#546E7A'],
                        'Lo-fi' => ['#8D6E63', '#3E2723'],
                        'Metal' => ['#424242', '#0D0D0D'],
                        'Punk' => ['#F44336', '#B71C1C'],
                        'Alternative' => ['#795548', '#3E2723'],
                        'Indie' => ['#9CCC65', '#558B2F'],
                        'K-Pop' => ['#EC407A', '#AD1457'],
                        'J-Pop' => ['#F06292', '#C2185B'],
                        'C-Pop' => ['#EF5350', '#C62828'],
                        'Anime' => ['#AB47BC', '#6A1B9A'],
                        'Soundtrack' => ['#5C6BC0', '#283593'],
                        'Instrumental' => ['#26A69A', '#00695C'],
                        'Acoustic' => ['#FFB74D', '#E65100'],
                        'Other' => ['#78909C', '#37474F'],
                    ];
                @endphp

                <div class="genre-grid">

                    @foreach($genres as $genre => $colors)

                        <a
                            href="{{ route('search', ['q' => $genre]) }}"
                            class="genre-card"
                            style="background: linear-gradient(135deg, {{ $colors[0] }} 0%, {{ $colors[1] }} 100%);"
                        >
                            <span class="genre-name">{{ $genre }}</span>
                        </a>

                    @endforeach

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

        if (window.__searchLoaded) return;
        window.__searchLoaded = true;


        // ==========================================================
        // SIDEBAR ACTIVE STATE
        // ==========================================================

        function updateSidebarActive() {

            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            sidebar.querySelectorAll('.nav-link, .library-item').forEach(el => {
                el.classList.remove('active');
            });

            const path = window.location.pathname;
            let activeKey = null;

            if (path.startsWith('/search') || path.startsWith('/songs') || path.startsWith('/artists')) {
                activeKey = 'search';
            } else if (path.startsWith('/home') || path === '/' || path.startsWith('/dashboard')) {
                activeKey = 'home';
            } else if (path.startsWith('/creator')) {
                activeKey = 'creator';
            } else if (path.startsWith('/playlists') || path.startsWith('/playlist')) {
                activeKey = 'playlist';
            } else if (path.startsWith('/favorites')) {
                activeKey = 'favorites';
            }

            if (activeKey) {
                const el = sidebar.querySelector('[data-nav="' + activeKey + '"]');
                if (el) el.classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', updateSidebarActive);
        document.addEventListener('turbo:load', updateSidebarActive);


        // ==========================================================
        // AUTOCOMPLETE
        // ==========================================================

        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        const suggestionsBox = document.getElementById('searchSuggestions');

        let searchTimeout = null;

        if (searchInput) {

            searchInput.addEventListener('input', function () {

                const query = this.value.trim();

                if (query.length === 0) {
                    suggestionsBox.innerHTML = '';
                    suggestionsBox.classList.remove('show');
                    return;
                }

                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {

                    fetch("{{ route('search.suggest') }}?q=" + encodeURIComponent(query), {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Request failed');
                        return response.json();
                    })
                    .then(results => {

                        suggestionsBox.innerHTML = '';

                        if (!results.length) {
                            suggestionsBox.innerHTML = '<div class="suggestion-empty">No suggestions found</div>';
                            suggestionsBox.classList.add('show');
                            return;
                        }

                        results.forEach(result => {

                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'suggestion-item';

                            let icon = '♫';
                            if (result.type === 'artist') icon = '●';
                            if (result.type === 'genre') icon = '▦';

                            item.innerHTML = `
                                <div class="suggestion-icon">${icon}</div>
                                <div class="suggestion-text">
                                    <div class="suggestion-title">${escapeHtml(result.text)}</div>
                                    <div class="suggestion-subtitle">${escapeHtml(result.subtext)}</div>
                                </div>
                            `;

                            item.addEventListener('click', function () {
                                searchInput.value = result.query;
                                suggestionsBox.innerHTML = '';
                                suggestionsBox.classList.remove('show');
                                searchForm.submit();
                            });

                            suggestionsBox.appendChild(item);
                        });

                        suggestionsBox.classList.add('show');
                    })
                    .catch(error => {
                        console.error(error);
                        suggestionsBox.innerHTML = '';
                        suggestionsBox.classList.remove('show');
                    });

                }, 150);
            });

            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    suggestionsBox.innerHTML = '';
                    suggestionsBox.classList.remove('show');
                    searchForm.submit();
                }
            });
        }

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.search-wrapper') && suggestionsBox) {
                suggestionsBox.innerHTML = '';
                suggestionsBox.classList.remove('show');
            }
        });

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text ?? '';
            return div.innerHTML;
        }


        // ==========================================================
        // MUSIC PLAYER
        // ==========================================================

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

        if (!globalAudio) return;

        let songItems = [];
        let currentIndex = -1;
        let shuffle = false;
        let repeat = false;
        let muted = false;

        function refreshSongItems() {
            const cards = Array.from(document.querySelectorAll('.song-card, .song-result'));
            songItems = cards;
        }

        function formatTime(seconds) {
            if (!seconds || isNaN(seconds)) return '0:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return m + ':' + (s < 10 ? '0' : '') + s;
        }

        playPauseBtn.addEventListener('click', () => {
            if (currentIndex === -1) {
                if (songItems.length > 0) playItem(0);
                return;
            }
            if (globalAudio.paused) globalAudio.play();
            else globalAudio.pause();
        });

        globalAudio.addEventListener('play', () => playIcon.setAttribute('points', '6 4 14 4 14 20 6 20'));
        globalAudio.addEventListener('pause', () => playIcon.setAttribute('points', '6 4 20 12 6 20 6 4'));

        function playItem(index, autoPlay = true) {
            if (index < 0 || index >= songItems.length) return;
            currentIndex = index;
            const item = songItems[index];

            playerTitle.textContent = item.dataset.title || 'Unknown';
            playerArtist.textContent = item.dataset.artist || 'Unknown';

            const cover = item.dataset.cover || '';
            playerCover.innerHTML = cover ? `<img src="${cover}" alt="">` : '〽';

            globalAudio.src = item.dataset.audio;
            globalAudio.load();
            if (autoPlay) globalAudio.play().catch(() => {});
        }

        function playNext() {
            if (songItems.length === 0) return;
            let nextIndex;
            if (shuffle) {
                if (songItems.length === 1) nextIndex = 0;
                else { do { nextIndex = Math.floor(Math.random() * songItems.length); } while (nextIndex === currentIndex); }
            } else {
                nextIndex = currentIndex + 1;
                if (nextIndex >= songItems.length) nextIndex = 0;
            }
            playItem(nextIndex);
        }

        nextBtn.addEventListener('click', playNext);
        prevBtn.addEventListener('click', () => {
            if (songItems.length === 0) return;
            let prevIndex = currentIndex - 1;
            if (prevIndex < 0) prevIndex = songItems.length - 1;
            playItem(prevIndex);
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

        refreshSongItems();
        document.addEventListener('turbo:load', refreshSongItems);

    })();

</script>

</body>
</html>