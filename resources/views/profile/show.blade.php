<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Wavo Music</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    (function () {

        if (window.__profilePhotoInited) return;
        window.__profilePhotoInited = true;

        const MAX_BYTES = 4096 * 1024;

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#profilePhotoBtn')) return;
            document.getElementById('profilePhotoInput')?.click();
        });

        document.addEventListener('change', function (e) {

            const input = e.target.closest('#profilePhotoInput');
            if (!input || !input.files || !input.files[0]) return;

            const file = input.files[0];
            const form = document.getElementById('profilePhotoForm');
            const btn = document.getElementById('profilePhotoBtn');
            const msg = document.getElementById('profilePhotoMsg');

            function say(text, type) {
                msg.textContent = text;
                msg.className = 'profile-photo-msg ' + (type || '');
            }

            if (!/^image\/(png|jpe?g|webp)$/.test(file.type)) {
                say('Please choose a JPG, PNG or WEBP image.', 'error');
                input.value = '';
                return;
            }

            if (file.size > MAX_BYTES) {
                say('Image is too large (max 4 MB).', 'error');
                input.value = '';
                return;
            }

            if (!window.WavoImages) {
                form.submit();
                return;
            }

            btn.disabled = true;
            say('Uploading…');

            window.WavoImages
                .uploadUserAvatar(form.action, file)
                .then(() => say('Profile picture updated.', 'ok'))
                .catch(err => say(err.message || 'Upload failed.', 'error'))
                .finally(() => {
                    btn.disabled = false;
                    input.value = '';
                });
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

        /* SIDEBAR (GLOBAL)
           Markup dari partials/sidebar.blade.php — CSS di bawah identik dengan Home
           supaya sidebar tampil sama di semua halaman. */
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

        .library-title { font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 0 12px; margin: 20px 0 8px; }
        .library-item { display: flex; align-items: center; gap: 10px; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; color: #b8b8b8; transition: background .15s, color .15s; }
        .library-item:hover { background: #383838; color: #f5f5f5; }
        .library-item.active { background: #383838; color: #f5f5f5; }
        .library-thumb { width: 26px; height: 26px; border-radius: 6px; background: #4a4a4a; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #c5a45c; overflow: hidden; }
        .library-thumb.round { border-radius: 50%; }

        /* Kartu user di dasar sidebar. `.profile-name` HANYA untuk nama kecil di sidebar.
           Nama besar di halaman Profile memakai `.profile-page-name` (lihat bagian PROFILE INFO). */
        .profile { display: flex; align-items: center; gap: 10px; padding: 10px 6px 0; border-top: 1px solid #3d3d3d; margin-top: auto; flex-shrink: 0; }
        .profile-avatar { width: 31px; height: 31px; border-radius: 50%; background: #111; display: flex; align-items: center; justify-content: center; color: #c5a45c; font-size: 12px; font-weight: bold; flex-shrink: 0; border: 1px solid #444; overflow: hidden; }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-name { color: #f5f5f5; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* MAIN */
        .main { flex: 1; min-width: 0; }
        .main.profile-main { padding: 0 0 0 25px; }

        /* BANNER */
        .profile-banner {
            position: relative;
            height: 240px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);
            background-image:
                radial-gradient(circle at 30% 40%, rgba(197, 164, 92, 0.15) 0%, transparent 60%),
                radial-gradient(circle at 70% 60%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            overflow: visible;
        }

        .profile-avatar-wrap {
            position: absolute;
            left: 40px;
            bottom: -70px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: #303030;
            overflow: hidden;
            border: 4px solid #303030;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            z-index: 5;
        }

        .profile-avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #c5a45c 0%, #8b6f2d 100%);
            color: #1a1a1a;
            font-size: 70px;
            font-weight: 800;
        }

        /* PROFILE INFO */
        .profile-info {
            padding: 90px 40px 40px;
        }

        .profile-page-name {
            font-size: 34px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
        }

        .profile-page-email {
            font-size: 14px;
            color: #999;
        }

                .profile-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; margin-top: 20px; }
        .profile-actions .profile-logout-form { margin-top: 0; }
        .profile-photo-form { display: inline-flex; }
        .profile-photo-btn {
            padding: 8px 16px; background: #3d3d3d; border-radius: 20px;
            color: #c5a45c; font-size: 12px; font-weight: 600; cursor: pointer;
            border: 1px solid transparent; transition: background .15s, border-color .15s;
        }
        .profile-photo-btn:hover { background: #4a4a4a; border-color: #c5a45c; }
        .profile-photo-btn:disabled { opacity: .6; cursor: progress; }
        .profile-photo-msg { font-size: 12px; color: #999; }
        .profile-photo-msg.ok { color: #81b29a; }
        .profile-photo-msg.error { color: #ff8a8a; }

        .profile-logout-form { margin-top: 20px; }

        .profile-logout-btn {
            padding: 8px 16px;
            background: #3d3d3d;
            border-radius: 20px;
            color: #e5a5a5;
            font-size: 12px;
            font-weight: 600;
            transition: background .15s, color .15s;
        }
        .profile-logout-btn:hover { background: #4a4a4a; color: #ff8a8a; }

        /* SECTION */
        .section {
            padding: 0 40px 60px;
        }

        .profile-section-title {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 24px;
        }

        /* PLAYLIST GRID */
        .profile-playlist-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .profile-playlist-card {
            cursor: pointer;
            min-width: 0;
        }

        .playlist-cover {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 10px;
            background: #4a4a4a;
            overflow: hidden;
            margin-bottom: 10px;
            transition: transform .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c5a45c;
            font-size: 36px;
            font-weight: 700;
        }

        .profile-playlist-card:hover .playlist-cover {
            transform: scale(1.02);
        }

        .playlist-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .playlist-name {
            font-size: 14px;
            font-weight: 600;
            color: #f5f5f5;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* EMPTY */
        .profile-empty {
            padding: 50px 25px;
            background: #363636;
            border: 1px solid #484848;
            border-radius: 12px;
            color: #999;
            text-align: center;
            font-size: 14px;
        }

        .profile-empty a {
            color: #c5a45c;
            font-weight: 600;
        }

        /* FOOTER */
        .profile-footer { width: 100%; min-height: 330px; padding: 55px 68px 40px; background: #4b4b4b; color: #aaa; }
        .profile-footer-language { display: flex; align-items: center; gap: 12px; margin-bottom: 56px; font-size: 14px; }
        .profile-footer-language .active-language { color: #eee; }
        .profile-footer-divider { width: 1px; height: 28px; background: #aaa; }
        .profile-footer-copy { margin-bottom: 50px; font-size: 14px; }
        .profile-footer-copy strong { color: #eee; font-weight: 500; }
        .profile-footer-links { display: flex; align-items: center; gap: 14px; font-size: 14px; }
        .profile-footer-link-divider { width: 1px; height: 28px; background: #999; }
        .profile-footer-links a:hover { color: #eee; }

        /* RESPONSIVE */
        @media (max-width: 1100px) {
            .profile-playlist-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 900px) {
            .profile-playlist-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 700px) {
            .layout { flex-direction: column; }
            .sidebar { position: relative; top: auto; margin: 15px; width: calc(100% - 30px); height: auto; max-height: none; }
            .main.profile-main { padding: 15px; }
            .profile-banner { height: 160px; }
            .profile-avatar-wrap { left: 20px; bottom: -50px; width: 120px; height: 120px; }
            .profile-info { padding: 70px 20px 30px; }
            .profile-page-name { font-size: 24px; }
            .section { padding: 0 20px 40px; }
            .profile-section-title { font-size: 20px; }
            .profile-footer { padding: 40px 25px; }
            .profile-footer-links { flex-wrap: wrap; }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/wavo-shell.css') }}">
</head>

<body>

<div class="layout">

    {{-- SIDEBAR (global, sama dengan halaman lain) --}}
    @include('partials.sidebar')


    {{-- MAIN --}}

    <main class="main profile-main">

        {{-- BANNER --}}
        <div class="profile-banner">

                        <div class="profile-avatar-wrap" data-user-avatar="{{ $user->id }}" data-image-alt="{{ $user->name }}">

                @if($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                @else
                    <div class="profile-avatar-placeholder">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

            </div>

        </div>


        {{-- PROFILE INFO --}}
        <div class="profile-info">

            <h1 class="profile-page-name">{{ $user->name }}</h1>

            <p class="profile-page-email">{{ $user->email }}</p>

            {{-- LOG OUT (sebelumnya ada di sidebar khusus Profile) --}}
                        <div class="profile-actions">

                <form method="POST" action="{{ route('profile.photo.update') }}"
                      enctype="multipart/form-data" class="profile-photo-form" id="profilePhotoForm">
                    @csrf
                    <input type="file" name="profile_photo" id="profilePhotoInput"
                           accept="image/png,image/jpeg,image/webp" hidden>
                    <button type="button" class="profile-photo-btn" id="profilePhotoBtn">
                        Change profile picture
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="profile-logout-form">
                    @csrf
                    <button type="submit" class="profile-logout-btn">Log out</button>
                </form>

                <span class="profile-photo-msg" id="profilePhotoMsg" role="status" aria-live="polite">
                    @if(session('success')) {{ session('success') }} @endif
                    @error('profile_photo') {{ $message }} @enderror
                </span>

            </div>

        </div>


        {{-- PUBLIC PLAYLIST --}}
        <div class="section">

            <h2 class="profile-section-title">Public Playlist</h2>

            @if($publicPlaylists->count() > 0)

                <div class="profile-playlist-grid">

                    @foreach($publicPlaylists as $playlist)

                        <a href="{{ route('playlist.show', $playlist) }}" class="profile-playlist-card">

                                                       <div class="playlist-cover" style="background: {{ $playlist->cover_color }};" data-playlist-cover="{{ $playlist->id }}" data-image-alt="{{ $playlist->name }}">
                                @if($playlist->cover_url)
                                    <img src="{{ $playlist->cover_url }}" alt="">
                                @else
                                    ♫
                                @endif
                            </div>

                            <div class="playlist-name">{{ $playlist->name }}</div>

                        </a>

                    @endforeach

                </div>

            @else

                <div class="profile-empty">
                    Belum ada playlist.
                </div>

            @endif

        </div>

    </main>

</div>


{{-- FOOTER --}}
<footer class="profile-footer">

    <div class="profile-footer-language">
        <span class="active-language">Indonesia</span>
        <span class="profile-footer-divider"></span>
        <span>Language English</span>
    </div>

    <div class="profile-footer-copy">
        Copyright © 2026 <strong>Wavo Interactive.</strong> All rights reserved.
    </div>

    <div class="profile-footer-links">
        <a href="#">Internet Service Terms</a>
        <span class="profile-footer-link-divider"></span>
        <a href="#">Wavo Music &amp; Privacy</a>
        <span class="profile-footer-link-divider"></span>
        <a href="#">Feedback</a>
        <span class="profile-footer-link-divider"></span>
        <a href="#">Support</a>
    </div>

</footer>

{{-- GLOBAL MUSIC PLAYER --}}
@include('partials.music-player')

<script>
    /* ==========================================================
       SIDEBAR ACTIVE STATE
       Sidebar global bersifat data-turbo-permanent, jadi elemen yang sama
       dipakai lintas halaman. Halaman Profile tidak punya item menu aktif,
       jadi cukup bersihkan state `active` sisa halaman sebelumnya.
       Hanya bertindak ketika URL = /profile agar tidak mengganggu halaman lain
       (listener di document tetap hidup setelah navigasi Turbo).
    ========================================================== */
    (function () {

        function clearSidebarActiveOnProfile() {

            if (!window.location.pathname.startsWith('/profile')) return;

            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            sidebar.querySelectorAll('.nav-link, .library-item').forEach(el => {
                el.classList.remove('active');
            });
        }

        if (!window.__wavoProfileSidebarSync) {
            window.__wavoProfileSidebarSync = true;
            document.addEventListener('DOMContentLoaded', clearSidebarActiveOnProfile);
            document.addEventListener('turbo:load', clearSidebarActiveOnProfile);
        }

    })();
</script>

</body>
</html>