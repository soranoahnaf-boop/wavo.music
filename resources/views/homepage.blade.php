<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Wavo Music</title>

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

        /* FILTER TABS */
        .filter-tabs { display: flex; gap: 6px; margin-bottom: 26px; }
        .filter-tab {
            padding: 7px 20px; border-radius: 20px;
            background: #3d3d3d; color: #b8b8b8;
            font-size: 12px; font-weight: 600;
            transition: background .15s, color .15s;
        }
        .filter-tab:hover { background: #4a4a4a; color: #f5f5f5; }
        .filter-tab.active { background: #5a5a5a; color: #fff; }

        /* PLAYLIST CARDS */
        .playlist-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 40px; }
        .playlist-card {
            display: flex; align-items: center; gap: 14px; padding: 12px;
            background: #3d3d3d; border-radius: 10px;
            transition: background .15s, transform .15s; cursor: pointer;
        }
        .playlist-card:hover { background: #4a4a4a; transform: translateY(-2px); }
        .playlist-card-cover {
            width: 62px; height: 62px; border-radius: 8px; background: #555;
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
            color: #c5a45c; font-size: 24px; overflow: hidden;
        }
        .playlist-card-cover img { width: 100%; height: 100%; object-fit: cover; }
        .playlist-card-text { min-width: 0; flex: 1; }
        .playlist-card-name {
            font-size: 14px; font-weight: 700; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 4px;
        }
        .playlist-card-count { font-size: 11px; color: #a8a8a8; }
        .playlist-empty {
            grid-column: 1 / -1; padding: 24px; border-radius: 10px;
            background: #3d3d3d; color: #999; font-size: 12px; text-align: center;
        }
        .playlist-empty a { color: #c5a45c; font-weight: 600; }

        /* SECTION */
        .music-section { margin-bottom: 42px; }
        .section-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 18px;
        }
        .section-title { font-size: 15px; font-weight: 600; color: #d5d5d5; }
        .section-link {
            font-size: 11px; color: #a8a8a8; font-weight: 500;
            transition: color .15s; text-decoration: none;
        }
        .section-link:hover { color: #c5a45c; text-decoration: underline; }

        /* SONG GRID */
        .song-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .song-card { position: relative; cursor: pointer; min-width: 0; }
        .song-cover {
            position: relative; width: 100%; aspect-ratio: 1 / 1;
            border-radius: 10px; background: #4a4a4a; overflow: hidden;
            margin-bottom: 10px; transition: transform .15s;
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
            position: absolute; inset: 0; background: rgba(0, 0, 0, 0.5);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity .15s; pointer-events: none;
        }
        .song-card:hover .song-play-overlay { opacity: 1; }
        .song-play-overlay::after {
            content: '';
            width: 44px; height: 44px; border-radius: 50%;
            background: #c5a45c;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231a1a1a'><polygon points='8 5 19 12 8 19 8 5'/></svg>");
            background-size: 20px; background-position: center; background-repeat: no-repeat;
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
        .favorite-button:active { transform: scale(0.95); }
        .fav-star { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; }
        .fav-star-outline { color: #6a6a6a; transition: color .2s; }
        .favorite-button:hover .fav-star-outline { color: #8a8a8a; }
        .fav-star-fill {
            color: #facc15; clip-path: inset(100% 0 0 0);
            transition: clip-path .5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .favorite-button.favorited .fav-star-fill { clip-path: inset(0 0 0 0); }
        .favorite-button.favorited { background: rgba(250, 204, 21, 0.15); }
        .favorite-button.favorited .fav-star-outline { color: #facc15; }

        /* MORE BUTTON */
        .more-button {
            position: absolute; bottom: 8px; right: 8px;
            width: 32px; height: 32px; border-radius: 50%;
            background: rgba(30, 30, 30, 0.9);
            display: flex; align-items: center; justify-content: center;
            color: #fff; z-index: 6; opacity: 0; transform: translateY(6px);
            transition: opacity .15s, transform .15s, background .15s;
        }
        .song-card:hover .more-button { opacity: 1; transform: translateY(0); }
        .more-button:hover { background: #c5a45c; color: #1a1a1a; transform: scale(1.08); }

        /* ARTIST GRID */
        .artist-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .artist-card { text-align: center; min-width: 0; cursor: pointer; }
        .artist-avatar {
            width: 100%; aspect-ratio: 1 / 1; border-radius: 50%;
            background: #4a4a4a; overflow: hidden;
            margin: 0 auto 10px;
            display: flex; align-items: center; justify-content: center;
            color: #c5a45c; font-size: 42px; font-weight: 700;
            transition: transform .15s;
        }
        .artist-card:hover .artist-avatar { transform: scale(1.03); }
        .artist-name {
            font-size: 13px; font-weight: 600; color: #f5f5f5;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 3px;
        }
        .artist-role { font-size: 11px; color: #888; }

        /* EMPTY */
        .empty {
            padding: 50px 25px; background: #363636;
            border: 1px solid #484848; border-radius: 12px;
            color: #999; text-align: center; font-size: 14px;
        }
        .empty a { color: #c5a45c; font-weight: 600; }

        /* GUEST HERO */
        .guest-hero {
            background: linear-gradient(135deg, #c5a45c 0%, #a8863f 100%);
            border-radius: 16px; padding: 70px 50px;
            text-align: center; margin-bottom: 40px;
        }
        .guest-hero-logo { font-size: 44px; color: #303030; margin-bottom: 14px; }
        .guest-hero-title { font-size: 38px; font-weight: 800; color: #fff; margin-bottom: 18px; }
        .guest-hero-text {
            font-size: 14px; color: rgba(255, 255, 255, 0.9);
            max-width: 520px; margin: 0 auto 30px; line-height: 1.5;
        }
        .guest-hero-btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 34px; background: #303030; color: #fff;
            border-radius: 24px; font-size: 14px; font-weight: 700;
            margin-bottom: 20px; transition: background .15s, transform .15s;
        }
        .guest-hero-btn:hover { background: #1a1a1a; transform: translateY(-1px); }
        .guest-hero-login {
            display: block; font-size: 12px; color: rgba(255, 255, 255, 0.85);
        }
        .guest-hero-login a { color: #fff; font-weight: 600; text-decoration: underline; }

        /* SONG MENU */
        .song-menu {
            position: fixed; background: #282828;
            border: 1px solid #3e3e3e; border-radius: 8px;
            padding: 6px; min-width: 240px; max-height: 420px; overflow-y: auto;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.65);
            z-index: 8000; display: none;
        }
        .song-menu.show { display: block; }
        .song-menu-header {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px 12px; border-bottom: 1px solid #3e3e3e;
            margin-bottom: 6px;
        }
        .song-menu-cover {
            width: 40px; height: 40px; border-radius: 6px; background: #3d3d3d;
            flex-shrink: 0; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            color: #c5a45c; font-size: 18px;
        }
        .song-menu-cover img { width: 100%; height: 100%; object-fit: cover; }
        .song-menu-info { min-width: 0; flex: 1; }
        .song-menu-title {
            font-size: 13px; font-weight: 600; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .song-menu-artist {
            font-size: 11px; color: #a8a8a8; margin-top: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .song-menu-item {
            display: flex; align-items: center; gap: 12px;
            width: 100%; padding: 10px 12px; border-radius: 6px;
            font-size: 12px; font-weight: 500; color: #e8e8e8;
            text-align: left; transition: background .15s;
        }
        .song-menu-item:hover { background: #3e3e3e; }
        .song-menu-item svg { width: 16px; height: 16px; flex-shrink: 0; color: #a8a8a8; }
        .song-menu-item:hover svg { color: #fff; }
        .song-menu-item .dot {
            width: 22px; height: 22px; border-radius: 6px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; color: #fff; font-weight: 700; overflow: hidden;
        }
        .song-menu-divider { height: 1px; background: #3e3e3e; margin: 6px 0; }
        .song-menu-subtitle {
            padding: 8px 12px 4px; font-size: 10px; color: #888;
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .song-menu-empty { padding: 8px 12px 12px; font-size: 11px; color: #888; }

        /* MODAL */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0, 0, 0, 0.65);
            z-index: 6000; display: none; align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-overlay.show { display: flex; }
        .modal {
            background: #3a3a3a; border: 1px solid #555; border-radius: 14px;
            padding: 28px; width: 100%; max-width: 380px;
        }
        .modal-title { font-size: 16px; font-weight: 700; margin-bottom: 18px; }
        .modal-input {
            width: 100%; height: 40px; background: #2a2a2a;
            border: 1px solid #4a4a4a; border-radius: 8px;
            color: #f5f5f5; padding: 0 14px; font-size: 13px;
            outline: none; margin-bottom: 18px;
            transition: border-color .15s, box-shadow .15s;
        }
        .modal-input:focus { border-color: #c5a45c; box-shadow: 0 0 0 3px rgba(197, 164, 92, 0.18); }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
        .modal-btn {
            padding: 9px 20px; border-radius: 22px;
            font-size: 12px; font-weight: 700; transition: background .15s;
        }
        .modal-btn.cancel { background: #4a4a4a; color: #e5e5e5; }
        .modal-btn.cancel:hover { background: #555; }
        .modal-btn.primary { background: #c5a45c; color: #1a1a1a; }
        .modal-btn.primary:hover { background: #d4b76a; }

        /* MUSIC PLAYER */
        .music-player {
            position: fixed; left: 50%; bottom: 24px; transform: translateX(-50%);
            width: 620px; max-width: calc(100% - 48px); height: 68px;
            background: #2a2a2a; border: 1px solid #555; border-radius: 34px;
            z-index: 2000; display: flex; align-items: center;
            box-sizing: border-box; padding: 0 22px; gap: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
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

        /* TOAST */
        .toast {
            position: fixed; bottom: 110px; left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: #2a2a2a; border: 1px solid #555;
            color: #f5f5f5; padding: 10px 20px; border-radius: 24px;
            font-size: 12px; font-weight: 600; z-index: 7000;
            opacity: 0; transition: opacity .25s, transform .25s;
            pointer-events: none;
        }
        .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        @media (max-width: 1100px) { .playlist-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 900px) { .song-grid, .artist-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 700px) {
            .layout { flex-direction: column; }
            .sidebar { position: relative; top: auto; margin: 15px; width: calc(100% - 30px); height: auto; max-height: none; }
            .main { padding: 15px 15px 130px; }
            .song-grid, .artist-grid { grid-template-columns: repeat(2, 1fr); }
            .playlist-grid { grid-template-columns: 1fr; }
            .music-player { width: calc(100% - 30px); padding: 0 15px; gap: 10px; }
            .player-info { display: none; }
            .guest-hero { padding: 40px 25px; }
            .guest-hero-title { font-size: 26px; }
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

            @auth

                <div class="filter-tabs">
                    <button type="button" class="filter-tab active" data-filter="all">All</button>
                    <button type="button" class="filter-tab" data-filter="music">Music</button>
                </div>


                <div class="filter-section" data-section="all">

                    @if($playlists->count() > 0)

                        <div class="section-head">
                            <div class="section-title">Your Playlists</div>
                            <a href="{{ route('playlist.index') }}" class="section-link">Show All</a>
                        </div>

                        <div class="playlist-grid">

                            @foreach($playlists as $playlist)

                                <a href="{{ route('playlist.show', $playlist) }}" class="playlist-card">

                                    <div class="playlist-card-cover" style="background: {{ $playlist->cover_color }}">
                                        @if($playlist->cover_path)
                                            <img src="{{ asset('storage/' . $playlist->cover_path) }}" alt="">
                                        @else
                                            ♫
                                        @endif
                                    </div>

                                    <div class="playlist-card-text">
                                        <div class="playlist-card-name">{{ $playlist->name }}</div>
                                        <div class="playlist-card-count">
                                            {{ $playlist->songs_count }} {{ $playlist->songs_count == 1 ? 'song' : 'songs' }}
                                        </div>
                                    </div>

                                </a>

                            @endforeach

                        </div>

                    @else

                        <div class="playlist-grid">
                            <div class="playlist-empty">
                                Belum ada playlist. <a href="{{ route('creator') }}">Bikin di Creator →</a>
                            </div>
                        </div>

                    @endif

                </div>


                @if($recommendedSongs->count() > 0)

                    <section class="music-section filter-section" data-section="all music">
                        <div class="section-head">
                            <div class="section-title">Non-stop music based on your favorite songs and artists.</div>
                            <a href="{{ route('songs.index', ['filter' => 'recommended']) }}" class="section-link">Show All</a>
                        </div>

                        <div class="song-grid">
                            @foreach($recommendedSongs->take(4) as $song)
                                @include('partials.song-card', ['song' => $song])
                            @endforeach
                        </div>
                    </section>

                @endif


                @if($recentSongs->count() > 0)

                    <section class="music-section filter-section" data-section="all music">
                        <div class="section-head">
                            <div class="section-title">Inspired by your recent activity</div>
                            <a href="{{ route('songs.index', ['filter' => 'recent']) }}" class="section-link">Show All</a>
                        </div>

                        <div class="song-grid">
                            @foreach($recentSongs->take(4) as $song)
                                @include('partials.song-card', ['song' => $song])
                            @endforeach
                        </div>
                    </section>

                @endif


                @if($songs->count() > 0)

                    <section class="music-section filter-section" data-section="all music">
                        <div class="section-head">
                            <div class="section-title">All Music</div>
                            <a href="{{ route('songs.index', ['filter' => 'all']) }}" class="section-link">Show All</a>
                        </div>

                        <div class="song-grid">
                            @foreach($songs->take(4) as $song)
                                @include('partials.song-card', ['song' => $song])
                            @endforeach
                        </div>
                    </section>

                @endif


                @if($artists->count() > 0)

                    <section class="music-section filter-section" data-section="all">
                        <div class="section-head">
                            <div class="section-title">Artists</div>
                            <a href="{{ route('artists.index') }}" class="section-link">Show All</a>
                        </div>

                        <div class="artist-grid">
                            @foreach($artists->take(4) as $artist)
                                <a href="{{ route('search', ['q' => $artist->artist]) }}" class="artist-card">
                                    <div class="artist-avatar">
                                        {{ strtoupper(substr($artist->artist, 0, 1)) }}
                                    </div>
                                    <div class="artist-name">{{ $artist->artist }}</div>
                                    <div class="artist-role">Artist</div>
                                </a>
                            @endforeach
                        </div>
                    </section>

                @endif


                @if($songs->count() === 0 && $playlists->count() === 0)

                    <div class="empty">
                        Belum ada musik di Wavo. Yuk upload lagu pertamamu di
                        <a href="{{ route('creator') }}">Creator</a>.
                    </div>

                @endif


            @else

                <div class="guest-hero">

                    <div class="guest-hero-logo">〽</div>

                    <h1 class="guest-hero-title">Don't Miss the Beat</h1>

                    <p class="guest-hero-text">
                        Create an account to save your favorite tracks, build custom playlists,
                        and stream unlimited music.
                    </p>

                    <a href="{{ route('register') }}" class="guest-hero-btn">
                        Join Wavo
                    </a>

                    <span class="guest-hero-login">
                        Already have an account? <a href="{{ route('login') }}">Log in</a>
                    </span>

                </div>

            @endauth

        </div>
    </main>

</div>



{{-- SONG MENU --}}
<div class="song-menu" id="songMenu">
    <div class="song-menu-header" id="songMenuHeader"></div>
    <div id="songMenuPlaylists"></div>
</div>



{{-- MODAL --}}
<div class="modal-overlay" id="newPlaylistModal">
    <div class="modal">
        <div class="modal-title">New Playlist</div>
        <input type="text" class="modal-input" id="newPlaylistInput" placeholder="Playlist name" maxlength="255">
        <div class="modal-actions">
            <button type="button" class="modal-btn cancel" id="newPlaylistCancel">Cancel</button>
            <button type="button" class="modal-btn primary" id="newPlaylistSave">Create</button>
        </div>
    </div>
</div>



{{-- TOAST --}}
<div class="toast" id="toast"></div>



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
    if (window.__homeLoaded) return;
    window.__homeLoaded = true;
    window.WAVO = window.WAVO || {
        csrf: '{{ csrf_token() }}',
        urls: {
            played: '{{ url('/home') }}',
            favorite: '{{ url('/home') }}',
            playlistStore: '{{ route('playlist.store') }}',
            playlistAddSong: '{{ url('/playlist') }}',
        },
        auth: {{ Auth::check() ? 'true' : 'false' }},
    };


    function formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return m + ':' + (s < 10 ? '0' : '') + s;
    }

    function toast(message) {
        const el = document.getElementById('toast');
        if (!el) return;
        el.textContent = message;
        el.classList.add('show');
        clearTimeout(el._timer);
        el._timer = setTimeout(() => el.classList.remove('show'), 2200);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }


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
    // PLAYER
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

    let songCards = [];
    let currentIndex = -1;
    let shuffle = false;
    let repeat = false;
    let muted = false;


    function refreshSongCards() {
        songCards = Array.from(document.querySelectorAll('.song-card'));
    }


    function playSong(index, autoPlay = true) {
        if (index < 0 || index >= songCards.length) return;

        currentIndex = index;
        const card = songCards[index];

        playerTitle.textContent = card.dataset.title || 'Unknown';
        playerArtist.textContent = card.dataset.artist || 'Unknown';

        if (card.dataset.cover) {
            playerCover.innerHTML = `<img src="${card.dataset.cover}" alt="">`;
        } else {
            playerCover.innerHTML = '〽';
        }

        globalAudio.src = card.dataset.audio;
        globalAudio.load();

        if (autoPlay) {
            globalAudio.play().catch(() => {});
        }

        if (WAVO.auth && card.dataset.songId) {
            fetch(WAVO.urls.played + '/' + card.dataset.songId + '/played', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': WAVO.csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).catch(() => {});
        }
    }


    playPauseBtn.addEventListener('click', () => {
        if (currentIndex === -1) {
            if (songCards.length > 0) playSong(0);
            return;
        }
        if (globalAudio.paused) {
            globalAudio.play();
        } else {
            globalAudio.pause();
        }
    });


    globalAudio.addEventListener('play', () => {
        playIcon.setAttribute('points', '6 4 14 4 14 20 6 20');
    });

    globalAudio.addEventListener('pause', () => {
        playIcon.setAttribute('points', '6 4 20 12 6 20 6 4');
    });


    function playNext() {
        if (songCards.length === 0) return;
        let nextIndex;
        if (shuffle) {
            if (songCards.length === 1) {
                nextIndex = 0;
            } else {
                do {
                    nextIndex = Math.floor(Math.random() * songCards.length);
                } while (nextIndex === currentIndex);
            }
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
        if (repeat) {
            globalAudio.currentTime = 0;
            globalAudio.play();
            return;
        }
        playNext();
    });


    shuffleBtn.addEventListener('click', () => {
        shuffle = !shuffle;
        shuffleBtn.classList.toggle('active', shuffle);
    });

    repeatBtn.addEventListener('click', () => {
        repeat = !repeat;
        repeatBtn.classList.toggle('active', repeat);
    });


    globalAudio.addEventListener('timeupdate', () => {
        if (!globalAudio.duration) return;
        const percent = (globalAudio.currentTime / globalAudio.duration) * 100;
        progressFill.style.width = percent + '%';
        currentTimeEl.textContent = formatTime(globalAudio.currentTime);
    });

    globalAudio.addEventListener('loadedmetadata', () => {
        totalTimeEl.textContent = formatTime(globalAudio.duration);
    });

    progressBar.addEventListener('click', (e) => {
        if (!globalAudio.duration) return;
        const rect = progressBar.getBoundingClientRect();
        const percent = (e.clientX - rect.left) / rect.width;
        globalAudio.currentTime = percent * globalAudio.duration;
    });


    muteBtn.addEventListener('click', () => {
        muted = !muted;
        globalAudio.muted = muted;
        muteBtn.style.color = muted ? '#d9534f' : '';
    });


    document.addEventListener('click', (e) => {
        const card = e.target.closest('.song-card');
        if (!card) return;
        if (e.target.closest('.favorite-button')) return;
        if (e.target.closest('.more-button')) return;

        refreshSongCards();
        const index = songCards.indexOf(card);
        if (index !== -1) playSong(index);
    });


    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.favorite-button');
        if (!btn) return;

        e.stopPropagation();
        e.preventDefault();

        if (!WAVO.auth) return;

        const songId = btn.dataset.songId;
        const wasFavorited = btn.classList.contains('favorited');

        document.querySelectorAll('.favorite-button[data-song-id="' + songId + '"]').forEach(item => {
            if (wasFavorited) item.classList.remove('favorited');
            else item.classList.add('favorited');
        });

        fetch(WAVO.urls.favorite + '/' + songId + '/favorite', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': WAVO.csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                document.querySelectorAll('.favorite-button[data-song-id="' + songId + '"]').forEach(item => {
                    if (wasFavorited) item.classList.add('favorited');
                    else item.classList.remove('favorited');
                });
                return;
            }

            document.querySelectorAll('.favorite-button[data-song-id="' + songId + '"]').forEach(item => {
                if (data.favorited) item.classList.add('favorited');
                else item.classList.remove('favorited');
            });

            toast(data.favorited ? 'Ditambahin ke Favourite ★' : 'Dihapus dari Favourite');
        })
        .catch(() => {});
    });


    const songMenu = document.getElementById('songMenu');
    const songMenuHeader = document.getElementById('songMenuHeader');
    const songMenuPlaylists = document.getElementById('songMenuPlaylists');
    let activeSongId = null;


    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.more-button');
        if (!btn) return;

        e.stopPropagation();
        e.preventDefault();

        if (!WAVO.auth) return;

        const card = btn.closest('.song-card');
        if (!card) return;

        activeSongId = card.dataset.songId;

        const cover = card.dataset.cover || '';
        songMenuHeader.innerHTML = `
            <div class="song-menu-cover">
                ${cover ? `<img src="${cover}" alt="">` : '♫'}
            </div>
            <div class="song-menu-info">
                <div class="song-menu-title">${escapeHtml(card.dataset.title || '')}</div>
                <div class="song-menu-artist">${escapeHtml(card.dataset.artist || '')}</div>
            </div>
        `;

        const rect = btn.getBoundingClientRect();
        songMenu.classList.add('show');

        requestAnimationFrame(() => {
            const menuRect = songMenu.getBoundingClientRect();
            let left = rect.right - menuRect.width;
            let top = rect.top - 8;

            if (top + menuRect.height > window.innerHeight) {
                top = rect.bottom - menuRect.height;
            }

            if (left < 10) left = 10;
            if (top < 10) top = 10;
            if (top + menuRect.height > window.innerHeight - 10) {
                top = window.innerHeight - menuRect.height - 10;
            }

            songMenu.style.left = left + 'px';
            songMenu.style.top = top + 'px';
        });

        loadPlaylistsForMenu();
    });


    document.addEventListener('click', (e) => {
        if (!e.target.closest('#songMenu') && !e.target.closest('.more-button')) {
            songMenu.classList.remove('show');
        }
    });

    document.addEventListener('scroll', () => {
        songMenu.classList.remove('show');
    }, true);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') songMenu.classList.remove('show');
    });


    function loadPlaylistsForMenu() {

        songMenuPlaylists.innerHTML = '<div class="song-menu-empty">Loading...</div>';

        fetch('{{ route('playlist.index') }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {

            songMenuPlaylists.innerHTML = '';

            const newBtn = document.createElement('button');
            newBtn.type = 'button';
            newBtn.className = 'song-menu-item';
            newBtn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>New playlist</span>
            `;
            newBtn.addEventListener('click', () => {
                songMenu.classList.remove('show');
                openNewPlaylistModal();
            });
            songMenuPlaylists.appendChild(newBtn);

            if (data.playlists && data.playlists.length > 0) {

                const divider = document.createElement('div');
                divider.className = 'song-menu-divider';
                songMenuPlaylists.appendChild(divider);

                const subtitle = document.createElement('div');
                subtitle.className = 'song-menu-subtitle';
                subtitle.textContent = 'Add to playlist';
                songMenuPlaylists.appendChild(subtitle);

                data.playlists.forEach(p => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'song-menu-item';

                    const initial = (p.name || '?').charAt(0).toUpperCase();
                    const cover = p.cover_url
                        ? `<img src="${p.cover_url}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">`
                        : initial;

                    btn.innerHTML = `
                        <span class="dot" style="background:${p.cover_color || '#4a4a4a'}">${cover}</span>
                        <span>${escapeHtml(p.name)}</span>
                    `;

                    btn.addEventListener('click', () => {
                        addSongToPlaylist(p.id, activeSongId);
                        songMenu.classList.remove('show');
                    });

                    songMenuPlaylists.appendChild(btn);
                });

            } else {
                const empty = document.createElement('div');
                empty.className = 'song-menu-empty';
                empty.textContent = 'Belum ada playlist lain.';
                songMenuPlaylists.appendChild(empty);
            }
        })
        .catch(() => {
            songMenuPlaylists.innerHTML = '<div class="song-menu-empty">Gagal load playlist.</div>';
        });
    }


    function addSongToPlaylist(playlistId, songId) {
        fetch(WAVO.urls.playlistAddSong + '/' + playlistId + '/song/' + songId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': WAVO.csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) toast('Ditambahin ke playlist ✓');
            else toast('Gagal nambahin ke playlist');
        })
        .catch(() => toast('Gagal nambahin ke playlist'));
    }


    const newPlaylistModal = document.getElementById('newPlaylistModal');
    const newPlaylistInput = document.getElementById('newPlaylistInput');
    const newPlaylistCancel = document.getElementById('newPlaylistCancel');
    const newPlaylistSave = document.getElementById('newPlaylistSave');


    function openNewPlaylistModal() {
        newPlaylistModal.classList.add('show');
        newPlaylistInput.value = '';
        newPlaylistInput.focus();
    }


    newPlaylistCancel.addEventListener('click', () => {
        newPlaylistModal.classList.remove('show');
    });


    newPlaylistModal.addEventListener('click', (e) => {
        if (e.target === newPlaylistModal) {
            newPlaylistModal.classList.remove('show');
        }
    });


    newPlaylistSave.addEventListener('click', () => {
        const name = newPlaylistInput.value.trim();

        if (!name) {
            toast('Nama playlist nggak boleh kosong');
            return;
        }

        fetch(WAVO.urls.playlistStore, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': WAVO.csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                toast('Gagal bikin playlist');
                return;
            }

            toast('Playlist dibuat ✓');

            if (activeSongId && data.playlist) {
                addSongToPlaylist(data.playlist.id, activeSongId);
            }

            newPlaylistModal.classList.remove('show');

            setTimeout(() => window.location.reload(), 1000);
        })
        .catch(() => toast('Gagal bikin playlist'));
    });


    refreshSongCards();

    document.addEventListener('turbo:load', () => {
        refreshSongCards();

        const activeFilter = document.querySelector('.filter-tab.active');
        if (activeFilter) {
            const filter = activeFilter.dataset.filter;
            document.querySelectorAll('.filter-section').forEach(section => {
                const tags = (section.dataset.section || '').split(' ');
                if (filter === 'all' || tags.includes(filter)) {
                    section.style.display = '';
                } else {
                    section.style.display = 'none';
                }
            });
        }
    });

    })();


    // ==========================================================
    // FILTER TABS — di LUAR IIFE, re-attach tiap Turbo navigasi
    // ==========================================================

    function initFilterTabs() {

        document.querySelectorAll('.filter-tab').forEach(tab => {

            if (tab.dataset.bound === '1') return;
            tab.dataset.bound = '1';

            tab.addEventListener('click', () => {

                const filter = tab.dataset.filter;

                if (filter === 'music') {
                    window.location.href = '{{ route('songs.index', ['filter' => 'recommended']) }}';
                    return;
                }

                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                document.querySelectorAll('.filter-section').forEach(section => {
                    section.style.display = '';
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', initFilterTabs);
    document.addEventListener('turbo:load', initFilterTabs);

</script>

</body>
</html>