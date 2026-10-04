<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Playlists - Wavo Music</title>

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

        .page-title { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .page-subtitle { font-size: 13px; color: #999; margin-bottom: 30px; }

        /* PLAYLIST GRID */
        .playlist-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .playlist-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px;
            background: #3d3d3d;
            border-radius: 12px;
            transition: background .15s, transform .15s;
            cursor: pointer;
        }

        .playlist-card:hover {
            background: #4a4a4a;
            transform: translateY(-3px);
        }

        .playlist-card-cover {
            width: 72px;
            height: 72px;
            border-radius: 10px;
            background: #555;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c5a45c;
            font-size: 28px;
            overflow: hidden;
        }

        .playlist-card-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .playlist-card-text {
            min-width: 0;
            flex: 1;
        }

        .playlist-card-name {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 5px;
        }

        .playlist-card-count {
            font-size: 11px;
            color: #a8a8a8;
        }

        /* PAGE HEAD + ADD BUTTON */
        .page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 30px;
        }
        .page-head .page-subtitle { margin-bottom: 0; }

        .add-playlist-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 22px;
            background: #c5a45c;
            color: #1a1a1a;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
            transition: background .15s, transform .15s;
        }
        .add-playlist-btn:hover { background: #d4b76a; transform: translateY(-1px); }
        .add-playlist-btn .plus { font-size: 16px; line-height: 1; }

        .empty .add-playlist-btn { margin-top: 18px; }

        /* MODAL */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 6000;
            display: none;
            align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-overlay.show { display: flex; }
        .modal {
            background: #3a3a3a; border: 1px solid #555; border-radius: 14px;
            padding: 28px; width: 100%; max-width: 380px;
        }
        .modal-title { font-size: 16px; font-weight: 700; margin-bottom: 18px; }
        .modal-input {
            width: 100%; height: 40px;
            background: #2a2a2a; border: 1px solid #4a4a4a; border-radius: 8px;
            color: #f5f5f5; padding: 0 14px; font-size: 13px; outline: none;
            margin-bottom: 18px;
            transition: border-color .15s, box-shadow .15s;
        }
        .modal-input:focus { border-color: #c5a45c; box-shadow: 0 0 0 3px rgba(197, 164, 92, 0.18); }
        .modal-error { color: #e07a5f; font-size: 12px; margin: -8px 0 14px; display: none; }
        .modal-error.show { display: block; }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
        .modal-btn { padding: 9px 20px; border-radius: 22px; font-size: 12px; font-weight: 700; transition: background .15s; }
        .modal-btn.cancel { background: #4a4a4a; color: #e5e5e5; }
        .modal-btn.cancel:hover { background: #555; }
        .modal-btn.primary { background: #c5a45c; color: #1a1a1a; }
        .modal-btn.primary:hover { background: #d4b76a; }
        .modal-btn:disabled { opacity: .6; cursor: default; }

        /* EMPTY */
        .empty {
            grid-column: 1 / -1;
            padding: 60px 30px;
            background: #363636;
            border: 1px solid #484848;
            border-radius: 12px;
            color: #999;
            text-align: center;
            font-size: 14px;
        }

        .empty a {
            color: #c5a45c;
            font-weight: 600;
        }

        /* RESPONSIVE */
        @media (max-width: 1100px) { .playlist-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 700px) {
            .layout { flex-direction: column; }
            .sidebar { position: relative; top: auto; margin: 15px; width: calc(100% - 30px); height: auto; max-height: none; }
            .main { padding: 15px 15px 130px; }
            .playlist-grid { grid-template-columns: 1fr; }
            .page-head { flex-direction: column; }
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

            <div class="page-head">
                <div>
                    <h1 class="page-title">Your Playlists</h1>
                    <p class="page-subtitle">
                        {{ $playlists->count() }} {{ $playlists->count() == 1 ? 'playlist' : 'playlists' }} yang kamu buat
                    </p>
                </div>

                <button type="button" class="add-playlist-btn" data-open-new-playlist>
                    <span class="plus">+</span> Add Playlist
                </button>
            </div>


            @if($playlists->count() > 0)

                <div class="playlist-grid">

                    @foreach($playlists as $playlist)

                        <a href="{{ route('playlist.show', $playlist) }}" class="playlist-card">

                                                        <div class="playlist-card-cover"
                                 style="background: {{ $playlist->cover_color }}"
                                 data-playlist-cover="{{ $playlist->id }}"
                                 data-image-alt="{{ $playlist->name }}">
                                @if($playlist->cover_url)
                                    <img src="{{ $playlist->cover_url }}" alt="">
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

                <div class="empty">
                    Belum ada playlist.
                </div>

            @endif

        </div>
    </main>

</div>



{{-- NEW PLAYLIST MODAL --}}
<div class="modal-overlay" id="newPlaylistModal">
    <div class="modal">
        <div class="modal-title">New Playlist</div>

        <input
            type="text"
            class="modal-input"
            id="newPlaylistInput"
            placeholder="Playlist name"
            maxlength="255"
            autocomplete="off"
        >

        <div class="modal-error" id="newPlaylistError"></div>

        <div class="modal-actions">
            <button type="button" class="modal-btn cancel" id="newPlaylistCancel">Cancel</button>
            <button type="button" class="modal-btn primary" id="newPlaylistSave">Create</button>
        </div>
    </div>
</div>


{{-- MUSIC PLAYER --}}
@include('partials.music-player')



<script>

    (function() {

        if (window.__playlistIndexInited) return;
        window.__playlistIndexInited = true;


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

<script>

    /* ==========================================================
       NEW PLAYLIST
       Pakai event delegation supaya tetap jalan setelah Turbo
       ganti isi halaman.
       ========================================================== */

    (function() {

        if (window.__newPlaylistInited) return;
        window.__newPlaylistInited = true;

        const storeUrl = @json(route('playlist.store'));
        const showBase = @json(url('/playlist'));

        const el = (id) => document.getElementById(id);

        function showError(msg) {
            const box = el('newPlaylistError');
            if (!box) return;
            box.textContent = msg;
            box.classList.add('show');
        }

        function openModal() {
            const modal = el('newPlaylistModal');
            if (!modal) return;
            el('newPlaylistError').classList.remove('show');
            el('newPlaylistInput').value = '';
            el('newPlaylistSave').disabled = false;
            modal.classList.add('show');
            el('newPlaylistInput').focus();
        }

        function closeModal() {
            const modal = el('newPlaylistModal');
            if (modal) modal.classList.remove('show');
        }

        function savePlaylist() {
            const input = el('newPlaylistInput');
            const saveBtn = el('newPlaylistSave');
            const name = input.value.trim();

            if (!name) {
                showError('Nama playlist nggak boleh kosong');
                return;
            }

            saveBtn.disabled = true;

            fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success || !data.playlist) {
                    saveBtn.disabled = false;
                    showError(data.message || 'Gagal bikin playlist');
                    return;
                }

                // Langsung buka playlist yang baru dibuat
                window.location.href = showBase + '/' + data.playlist.id;
            })
            .catch(() => {
                saveBtn.disabled = false;
                showError('Gagal bikin playlist');
            });
        }

        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-open-new-playlist]')) {
                openModal();
            } else if (e.target.closest('#newPlaylistCancel')) {
                closeModal();
            } else if (e.target.closest('#newPlaylistSave')) {
                savePlaylist();
            } else if (e.target.id === 'newPlaylistModal') {
                closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            const modal = el('newPlaylistModal');
            if (!modal || !modal.classList.contains('show')) return;

            if (e.key === 'Escape') closeModal();
            if (e.key === 'Enter' && e.target.id === 'newPlaylistInput') {
                e.preventDefault();
                savePlaylist();
            }
        });

    })();

</script>

</body>
</html>
