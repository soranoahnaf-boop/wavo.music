<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $playlist->name }} - Wavo Music</title>

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

        /* HERO BANNER */
        .hero {
            position: relative;
            height: 340px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            padding: 40px 50px;
            margin-bottom: 40px;
            border-radius: 0 0 0 13px;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
            filter: blur(20px) brightness(0.6);
            transform: scale(1.1); z-index: 0;
        }
        .hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(48,48,48,0) 0%, rgba(48,48,48,0.85) 100%);
            z-index: 1;
        }
        .hero-content {
            position: relative; z-index: 2;
            display: flex; align-items: flex-end; gap: 28px; width: 100%;
        }
        .hero-cover {
            width: 180px; height: 180px; border-radius: 12px; background: #474747;
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
            font-size: 50px; color: #888;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5); overflow: hidden;
        }
        .hero-cover img { width: 100%; height: 100%; object-fit: cover; }
        .hero-text { flex: 1; min-width: 0; padding-bottom: 8px; }
        .hero-type {
            font-size: 11px; color: #c5a45c; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
        }
        .hero-title {
            font-size: 44px; font-weight: 800; color: #fff;
            margin-bottom: 12px; line-height: 1.05; word-break: break-word;
        }
        .hero-meta { font-size: 12px; color: #b8b8b8; margin-bottom: 18px; }
        .hero-play {
            width: 56px; height: 56px; border-radius: 50%;
            background: #c5a45c; color: #1a1a1a;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; transition: transform .15s, background .15s;
        }
        .hero-play:hover { transform: scale(1.05); background: #d4b76a; }

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

        .song-number {
            font-size: 14px; color: #888; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
        }

        /* WAVEFORM */
        .waveform {
            display: none;
            align-items: flex-end;
            justify-content: center;
            gap: 2px;
            width: 18px;
            height: 16px;
        }
        .waveform span {
            display: block;
            width: 2px;
            background: currentColor;
            border-radius: 1px;
            animation: waveform 0.9s ease-in-out infinite;
        }
        .waveform span:nth-child(1) { animation-delay: -0.8s; height: 40%; }
        .waveform span:nth-child(2) { animation-delay: -0.6s; height: 70%; }
        .waveform span:nth-child(3) { animation-delay: -0.4s; height: 100%; }
        .waveform span:nth-child(4) { animation-delay: -0.2s; height: 60%; }
        .waveform span:nth-child(5) { animation-delay: 0s;    height: 80%; }

        @keyframes waveform {
            0%, 100% { transform: scaleY(0.4); }
            50%      { transform: scaleY(1); }
        }

        .song-row .song-number-text { display: inline; }
        .song-row .waveform { display: none; }
        .song-row.playing .song-number-text { display: none; }
        .song-row.playing .waveform { display: flex; }
        .song-row.playing .waveform span { background: #1a1a1a; }
        .song-row:not(.playing) .waveform span { background: #888; }

        .song-number svg { width: 16px; height: 16px; }
        .song-info { min-width: 0; }
        .song-title {
            font-size: 14px; font-weight: 600; color: #f5f5f5;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .song-row.playing .song-title { color: #1a1a1a; }
        .song-artist {
            font-size: 11px; color: #999; margin-top: 3px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .song-row.playing .song-artist { color: #3a2d10; }
        .song-duration {
            font-size: 12px; color: #888; text-align: right;
            font-variant-numeric: tabular-nums;
        }

        /* EMPTY */
        .empty { padding: 60px 30px; text-align: center; color: #888; font-size: 14px; }
        .empty-icon { font-size: 40px; color: #c5a45c; margin-bottom: 12px; }

        /* OWNER CONTROLS */
        .owner-controls { display: flex; gap: 10px; margin-top: 12px; }
        .owner-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px; background: #3d3d3d;
            border: 1px solid #555; border-radius: 20px;
            color: #e5e5e5; font-size: 11px; font-weight: 600;
            cursor: pointer; transition: background .15s, border-color .15s;
        }
        .owner-btn:hover { background: #4a4a4a; border-color: #c5a45c; }
        .owner-btn.danger:hover { border-color: #d9534f; color: #ff8a8a; }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero { padding: 30px 25px; height: 280px; }
            .hero-cover { width: 130px; height: 130px; }
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
            .hero-title { font-size: 26px; }
            .song-list-wrapper { padding: 0 15px; }
        }
    </style>
</head>

<body>

<div class="layout">

    {{-- SIDEBAR --}}
    @include('partials.sidebar')


    {{-- MAIN --}}

    <main class="main">

        <div class="hero">

            @if($playlist->cover_path)
                <div class="hero-bg" style="background-image: url('{{ asset('storage/' . $playlist->cover_path) }}')"></div>
            @else
                <div class="hero-bg" style="background: linear-gradient(135deg, #3a3a3a 0%, #2a2a2a 100%);"></div>
            @endif

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <div class="hero-cover">
                    @if($playlist->cover_path)
                        <img src="{{ asset('storage/' . $playlist->cover_path) }}" alt="{{ $playlist->name }}">
                    @else
                        <span style="color: #666; font-size: 60px;">♫</span>
                    @endif
                </div>

                <div class="hero-text">

                    <div class="hero-type">Playlist</div>

                    <div class="hero-title">{{ $playlist->name }}</div>

                    <div class="hero-meta">
                        {{ $playlist->user->name }} ·
                        {{ $playlist->songs->count() }} songs ·
                        {{ gmdate('i:s', $playlist->total_duration) }} total
                    </div>

                    <button
                        type="button"
                        class="hero-play"
                        id="heroPlayBtn"
                        data-first-song="{{ $playlist->songs->first()->id ?? '' }}"
                        title="Play all"
                    >
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="6 4 20 12 6 20 6 4"></polygon>
                        </svg>
                    </button>

                    @if($isOwner)
                        <div class="owner-controls">

                            <form action="{{ route('playlist.uploadCover', $playlist) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  style="display:inline;">
                                @csrf
                                <input type="file" name="cover" id="playlistCoverInput" hidden accept="image/*">
                                <label for="playlistCoverInput" class="owner-btn">Change cover</label>
                            </form>

                            <form action="{{ route('playlist.destroy', $playlist) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this playlist?')"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="owner-btn danger">Delete playlist</button>
                            </form>

                        </div>
                    @endif

                </div>

            </div>

        </div>



        {{-- SONG LIST --}}

        <div class="song-list-wrapper">

            @if($playlist->songs->count() > 0)

                <div class="song-list" id="songList">

                    @foreach($playlist->songs as $index => $song)

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
                    <div class="empty-icon">♫</div>
                    <div>This playlist is empty.</div>
                </div>

            @endif

        </div>

    </main>

</div>



{{-- MUSIC PLAYER --}}
@include('partials.music-player')



<script>

    (function() {

        if (window.__playlistShowInited) return;
        window.__playlistShowInited = true;

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