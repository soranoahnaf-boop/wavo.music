<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists - Wavo Music</title>

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
        .main { flex: 1; min-width: 0; padding: 24px 38px 130px 25px; }
        .content { width: 100%; max-width: 1180px; }

        /* BACK */
        .back-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 16px; background: #3d3d3d; border-radius: 20px;
            color: #b8b8b8; font-size: 12px; font-weight: 600;
            margin-bottom: 24px; transition: background .15s, color .15s;
        }
        .back-btn:hover { background: #4a4a4a; color: #fff; }

        /* HEADER */
        .page-header { margin-bottom: 30px; }
        .page-title { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 6px; }
        .page-subtitle { font-size: 13px; color: #999; }

        /* ARTIST GRID */
        .artist-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 22px;
        }

        .artist-card {
            text-align: center;
            min-width: 0;
            cursor: pointer;
            transition: transform .15s;
        }
        .artist-card:hover { transform: translateY(-3px); }

        .artist-avatar {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a4a4a 0%, #2e2e2e 100%);
            overflow: hidden;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c5a45c;
            font-size: 48px;
            font-weight: 800;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            transition: box-shadow .2s;
        }
        .artist-card:hover .artist-avatar {
            box-shadow: 0 12px 32px rgba(197, 164, 92, 0.25);
        }
        .artist-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .artist-name {
            font-size: 14px;
            font-weight: 700;
            color: #f5f5f5;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
        }
        .artist-count { font-size: 11px; color: #888; }

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

        /* RESPONSIVE */
        @media (max-width: 1100px) { .artist-grid { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 900px) { .artist-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 700px) {
            .layout { flex-direction: column; }
            .sidebar { position: relative; top: auto; margin: 15px; width: calc(100% - 30px); height: auto; max-height: none; }
            .main { padding: 15px 15px 130px; }
            .artist-grid { grid-template-columns: repeat(2, 1fr); }
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
        <div class="content">

            <a href="{{ route('home') }}" class="back-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Back to Home
            </a>

            <div class="page-header">
                <h1 class="page-title">Artists</h1>
                <p class="page-subtitle">{{ $artists->count() }} {{ $artists->count() == 1 ? 'artist' : 'artists' }} di Wavo</p>
            </div>


            @if($artists->count() > 0)

                <div class="artist-grid">

                    @foreach($artists as $artist)

                        <a href="{{ route('search', ['q' => $artist->artist]) }}" class="artist-card">

                            <div class="artist-avatar">
                                {{ strtoupper(substr($artist->artist, 0, 1)) }}
                            </div>

                            <div class="artist-name">{{ $artist->artist }}</div>
                            <div class="artist-count">
                                {{ $artist->total }} {{ $artist->total == 1 ? 'song' : 'songs' }}
                            </div>

                        </a>

                    @endforeach

                </div>

            @else

                <div class="empty">
                    Belum ada artist di Wavo.
                </div>

            @endif

        </div>
    </main>

</div>



{{-- MUSIC PLAYER --}}
@include('partials.music-player')



<script>

    (function() {

        if (window.__artistsPageInited) return;
        window.__artistsPageInited = true;


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