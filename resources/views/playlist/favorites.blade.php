<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites - Wavo Music</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://unpkg.com/@hotwired/turbo@8.0.0/dist/turbo.es2017-umd.js"></script>

    <link rel="stylesheet" href="{{ asset('css/player.css') }}">

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
        .library-item.active { background: #383838; color: #f5f5f5; }
        .library-thumb { width: 26px; height: 26px; border-radius: 6px; background: #4a4a4a; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #c5a45c; overflow: hidden; }
        .library-thumb.round { border-radius: 50%; }

        .profile { display: flex; align-items: center; gap: 10px; padding: 10px 6px 0; border-top: 1px solid #3d3d3d; margin-top: auto; flex-shrink: 0; }
        .profile-avatar { width: 31px; height: 31px; border-radius: 50%; background: #111; display: flex; align-items: center; justify-content: center; color: #c5a45c; font-size: 12px; font-weight: bold; flex-shrink: 0; border: 1px solid #444; overflow: hidden; }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-name { color: #f5f5f5; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* MAIN */
        .main { flex: 1; min-width: 0; padding: 0 0 130px; }

        /* HERO — kuning gradien + bintang */
        .hero {
            position: relative;
            height: 340px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            padding: 40px 50px;
            margin-bottom: 40px;
            border-radius: 0 0 0 13px;
            background: linear-gradient(135deg, #f5c842 0%, #b8860b 100%);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(48,48,48,0) 0%, rgba(48,48,48,0.6) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: flex-end;
            gap: 28px;
            width: 100%;
        }

        .hero-cover {
            width: 180px;
            height: 180px;
            border-radius: 20px;
            background: linear-gradient(135deg, #f5c842 0%, #b8860b 100%);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            overflow: hidden;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .hero-cover svg {
            width: 100px;
            height: 100px;
            color: #1a1a1a;
        }

        .hero-text {
            flex: 1;
            min-width: 0;
            padding-bottom: 8px;
        }

        .hero-type {
            font-size: 11px;
            color: #1a1a1a;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            background: rgba(255,255,255,0.3);
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
        }

        .hero-title {
            font-size: 44px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 12px;
            line-height: 1.05;
            word-break: break-word;
            text-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .hero-meta {
            font-size: 12px;
            color: rgba(255,255,255,0.85);
            margin-bottom: 18px;
        }

        .hero-play {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #1a1a1a;
            color: #f5c842;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            transition: transform .15s, background .15s;
        }

        .hero-play:hover {
            transform: scale(1.05);
            background: #000;
        }

        /* SONG LIST */
        .song-list-wrapper { padding: 0 50px; }
        .song-list { display: flex; flex-direction: column; gap: 2px; }

        .song-row {
            display: grid;
            grid-template-columns: 40px 1fr 80px;
            align-items: center;
            gap: 16px;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background .15s;
        }
        .song-row:hover { background: #383838; }
        .song-row.playing { background: #c5a45c; color: #1a1a1a; }
        .song-row.playing .song-number { color: #1a1a1a; }
        .song-row.playing .song-duration { color: #1a1a1a; }
        .song-row.playing .song-title { color: #1a1a1a; }
        .song-row.playing .song-artist { color: #3a2d10; }

        .song-number { font-size: 14px; color: #888; font-weight: 600; display: flex; align-items: center; justify-content: center; }
        .song-number svg { width: 16px; height: 16px; }
        .song-info { min-width: 0; }
        .song-title { font-size: 14px; font-weight: 600; color: #f5f5f5; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .song-artist { font-size: 11px; color: #999; margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .song-duration { font-size: 12px; color: #888; text-align: right; font-variant-numeric: tabular-nums; }

        .empty { padding: 60px 30px; text-align: center; color: #888; font-size: 14px; }
        .empty-icon { font-size: 40px; color: #c5a45c; margin-bottom: 12px; }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero { padding: 30px 25px; height: 280px; }
            .hero-cover { width: 130px; height: 130px; }
            .hero-cover svg { width: 70px; height: 70px; }
            .hero-title { font-size: 30px; }
            .song-list-wrapper { padding: 0 25px; }
            .song-row { grid-template-columns: 30px 1fr 60px; padding: 8px 12px; }
        }

        @media (max-width: 700px) {
            .layout { flex-direction: column; }
            .sidebar { position: relative; top: auto; margin: 15px; width: calc(100% - 30px); height: auto; max-height: none; }
            .hero { padding: 25px 20px; height: auto; min-height: 220px; border-radius: 0; }
            .hero-content { flex-direction: column; align-items: flex-start; }
            .hero-cover { width: 110px; height: 110px; }
            .hero-cover svg { width: 60px; height: 60px; }
            .hero-title { font-size: 26px; }
            .song-list-wrapper { padding: 0 15px; }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/wavo-shell.css') }}">
</head>

<body>

<div class="layout">

    {{-- SIDEBAR --}}
    @include('partials.sidebar')


    {{-- MAIN --}}

    <main class="main">

        {{-- HERO — kuning gradien + bintang --}}

        <div class="hero">

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <div class="hero-cover">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>

                <div class="hero-text">

                    <div class="hero-type">Playlist</div>

                    <div class="hero-title">Favourite</div>

                    <div class="hero-meta">
                        {{ $songs->count() }} {{ $songs->count() == 1 ? 'song' : 'songs' }} yang kamu suka
                    </div>

                    @if($songs->count() > 0)
                        <button
                            type="button"
                            class="hero-play"
                            id="heroPlayBtn"
                            title="Play all"
                        >
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="6 4 20 12 6 20 6 4"></polygon>
                            </svg>
                        </button>
                    @endif

                </div>

            </div>

        </div>



        {{-- SONG LIST --}}

        <div class="song-list-wrapper">

            @if($songs->count() > 0)

                <div class="song-list" id="songList" data-now-playing-icon="bars">

                    @foreach($songs as $index => $song)

                        <div
                            class="song-row"
                            data-song-id="{{ $song->id }}"
                            data-audio="{{ asset('storage/' . $song->audio_path) }}"
                            data-title="{{ $song->title }}"
                            data-artist="{{ $song->artist }}"
                            data-cover="{{ $song->cover_path ? asset('storage/' . $song->cover_path) : '' }}"
                            data-index="{{ $index }}"
                        >

                            <div class="song-number">
                                {{ $index + 1 }}
                            </div>

                            <div class="song-info">
                                <div class="song-title">{{ $song->title }}</div>
                                <div class="song-artist">{{ $song->artist }}</div>
                            </div>

                            <div class="song-duration">
                                {{ $song->duration_formatted }}
                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty">
                    <div class="empty-icon">★</div>
                    <div>Belum ada lagu favorit. Klik bintang di card lagu buat nambahin.</div>
                </div>

            @endif

        </div>

    </main>

</div>



{{-- MUSIC PLAYER --}}
@include('partials.music-player')



<script>

    (function() {

        if (window.__favoritesInited) return;
        window.__favoritesInited = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        /* ==========================================================
           SIDEBAR ACTIVE STATE
           ========================================================== */

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


        /* ==========================================================
           MUSIC PLAYER
           Seluruh logic player ada di partials/music-player.blade.php
           (window.WavoMusicPlayer). Halaman ini sengaja TIDAK memasang
           listener / logic player sendiri.
           ========================================================== */

    })();

</script>

</body>
</html>