<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Creator - Wavo Music</title>

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
        html { min-height: 100%; }
        body { min-height: 100vh; background: #303030; color: #f5f5f5; font-family: Arial, Helvetica, sans-serif; }
        a { color: inherit; text-decoration: none; }
        button, input, textarea, select { font-family: inherit; }
        button { border: none; cursor: pointer; }

        .page { min-height: 100vh; display: flex; flex-direction: column; }
        .layout { display: flex; align-items: flex-start; flex: 1; }

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
        .main { flex: 1; min-width: 0; margin-left: 25px; padding: 24px 38px 100px 0; }
        .content { width: 100%; max-width: 1180px; }
        .page-title { margin-bottom: 28px; font-size: 21px; font-weight: 700; line-height: 1; }

        /* UPLOADED SONG SECTION */
        .uploaded-section { margin-bottom: 30px; }
        .uploaded-title {
            font-size: 14px; font-weight: 700; color: #f5f5f5;
            margin-bottom: 14px; display: flex; align-items: center; gap: 6px;
        }
        .uploaded-count { color: #888; font-weight: 500; font-size: 13px; }

        .song-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 14px;
        }

        .song-grid.hidden-songs .song-card.extra { display: none; }

        .show-more-songs {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px; background: #3d3d3d;
            border: 1px solid #555; border-radius: 20px;
            color: #d2ad60; font-size: 12px; font-weight: 600;
            cursor: pointer; transition: background .15s, border-color .15s;
            margin-bottom: 22px;
        }
        .show-more-songs:hover { background: #4a4a4a; border-color: #c5a45c; }
        .show-more-songs svg { transition: transform .2s; }

        .song-card { position: relative; cursor: pointer; min-width: 0; }

        .song-cover {
            position: relative; width: 100%; aspect-ratio: 1 / 1;
            border-radius: 8px; background: #4a4a4a; overflow: hidden;
            margin-bottom: 8px; transition: transform .15s;
        }
        .song-card:hover .song-cover { transform: scale(1.02); }

        .song-cover img {
            width: 100%; height: 100%; object-fit: cover; display: block;
            pointer-events: none;
        }

        .song-cover-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: #888; font-size: 36px;
        }

        .song-title {
            font-size: 12px; font-weight: 600; color: #f5f5f5;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 3px;
        }
        .song-artist {
            font-size: 10px; color: #999;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* DELETE BUTTON */
        .delete-btn {
            position: absolute; top: 6px; right: 6px;
            width: 26px; height: 26px; border-radius: 50%;
            background: rgba(30, 30, 30, 0.9);
            display: flex; align-items: center; justify-content: center;
            color: #ddd; z-index: 6;
            opacity: 0; transform: translateY(-4px);
            transition: opacity .15s, transform .15s, background .15s, color .15s;
        }
        .song-card:hover .delete-btn { opacity: 1; transform: translateY(0); }
        .delete-btn:hover { background: #d9534f; color: #fff; }
        .delete-btn svg { width: 13px; height: 13px; }

        /* EMPTY STATE */
        .empty-songs {
            padding: 30px 25px; background: #363636;
            border: 1px solid #484848; border-radius: 12px;
            color: #999; text-align: center; font-size: 12px;
            margin-bottom: 22px;
        }

        /* UPLOAD TOGGLE */
        .upload-toggle { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; color: #f4f4f4; font-size: 13px; font-weight: 600; }
        .upload-toggle-button {
            width: 28px; height: 28px; border-radius: 8px; background: #4c4c4c; color: #d5d5d5;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 300; line-height: 1; transition: .15s;
        }
        .upload-toggle-button:hover { background: #5a5a5a; }

        .upload-area { display: none; grid-template-columns: 1fr 1fr; gap: 18px; width: 100%; }
        .upload-area.show { display: grid; }

        .upload-form { padding: 40px 32px; background: #474747; border-radius: 14px; }
        .form-row { display: grid; grid-template-columns: 120px 1fr; align-items: center; gap: 14px; margin-bottom: 16px; }
        .form-label { font-size: 13px; font-weight: 600; color: #e6e6e6; }

        .file-button {
            width: 54px; height: 54px; border-radius: 10px;
            background: #5a5a5a; border: 1px dashed #7a7a7a;
            display: flex; align-items: center; justify-content: center;
            color: #d0d0d0; font-size: 26px; font-weight: 200;
            cursor: pointer; transition: .15s;
        }
        .file-button:hover { background: #666; border-color: #c5a45c; color: #c5a45c; }
        .file-name { margin-top: 6px; max-width: 230px; color: #bdbdbd; font-size: 11px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .text-input {
            width: 100%; height: 38px; border: 1px solid #3d3d3d; outline: none;
            border-radius: 8px; background: #2e2e2e; color: #f5f5f5;
            padding: 0 13px; font-size: 13px; transition: border-color .15s, box-shadow .15s;
        }
        .text-input::placeholder { color: #888; }
        .text-input:focus { border-color: #c5a45c; box-shadow: 0 0 0 3px rgba(197, 164, 92, .18); }
        .artist-input { color: #f5f5f5; cursor: text; }
        .description-input { height: 80px; padding-top: 10px; padding-bottom: 10px; resize: vertical; }

        .genre-select {
            width: 100%; height: 38px; border: 1px solid #3d3d3d; outline: none;
            border-radius: 8px; background: #2e2e2e; color: #f5f5f5;
            padding: 0 13px; font-size: 13px; cursor: pointer; transition: border-color .15s, box-shadow .15s;
        }
        .genre-select:focus { border-color: #c5a45c; box-shadow: 0 0 0 3px rgba(197, 164, 92, .18); }
        .genre-select option { background: #2e2e2e; color: #f5f5f5; }

        .form-actions { display: flex; justify-content: center; margin-top: 36px; }
        .save-draft {
            min-width: 152px; height: 43px; padding: 0 25px;
            border-radius: 22px; background: #d2ad60; color: #1a1a1a;
            font-size: 14px; font-weight: 700; border: 1px solid #e2c27e; transition: .15s;
        }
        .save-draft:hover { background: #dfbb70; transform: translateY(-1px); }

        /* AGREEMENT */
        .agreement { padding: 28px 32px; background: #575757; border-radius: 14px; display: flex; flex-direction: column; }
        .agreement-logo { display: flex; justify-content: center; margin-bottom: 6px; color: #c5a45c; font-size: 31px; }
        .agreement-title { text-align: center; font-size: 16px; font-weight: 700; margin-bottom: 5px; }
        .agreement-subtitle { text-align: center; color: #eee; font-size: 11px; font-weight: 500; margin-bottom: 5px; letter-spacing: .3px; }
        .agreement-date { text-align: center; color: #9d9d9d; font-size: 11px; margin-bottom: 18px; }
        .agreement-scroll { height: 280px; overflow: hidden; padding-right: 4px; transition: overflow .2s; }
        .agreement.expanded .agreement-scroll { overflow-y: auto; padding-right: 12px; }
        .agreement-scroll::-webkit-scrollbar { width: 7px; }
        .agreement-scroll::-webkit-scrollbar-track { background: #444; border-radius: 10px; }
        .agreement-scroll::-webkit-scrollbar-thumb { background: #292929; border-radius: 10px; }
        .agreement-section { margin-bottom: 15px; }
        .agreement-section-title { margin-bottom: 3px; font-size: 12px; font-weight: 700; color: #f5f5f5; }
        .agreement-section-text { color: #d8d8d8; font-size: 11px; line-height: 1.45; font-weight: 400; }
        .agreement-check { display: flex; align-items: flex-start; gap: 9px; margin-top: 18px; color: #c5c5c5; font-size: 11px; line-height: 1.4; cursor: pointer; flex-shrink: 0; }
        .agreement-check input { width: 17px; height: 17px; flex-shrink: 0; margin-top: 1px; accent-color: #d2ad60; cursor: pointer; }
        .show-more { display: inline-block; width: fit-content; margin-top: 14px; color: #d2ad60; font-size: 11px; font-weight: 500; cursor: pointer; background: transparent; flex-shrink: 0; }
        .show-more:hover { color: #e2c17b; text-decoration: underline; }
        .publish-wrapper { display: flex; justify-content: center; margin-top: 22px; flex-shrink: 0; }
        .publish-button {
            min-width: 150px; height: 43px; padding: 0 27px;
            border-radius: 22px; background: #d2ad60; border: 1px solid #e2c27e;
            color: #1a1a1a; font-size: 14px; font-weight: 700; transition: .15s;
        }
        .publish-button:hover:not(:disabled) { background: #dfbb70; transform: translateY(-1px); }
        .publish-button:disabled { background: #6b6b6b; border-color: #7a7a7a; color: #b8b8b8; cursor: not-allowed; opacity: .8; }

        /* MESSAGE */
        .message { width: 100%; padding: 12px 15px; margin-bottom: 15px; border-radius: 8px; font-size: 12px; }
        .message-success { background: #3d533e; color: #d8f0da; border: 1px solid #4d6b4e; }
        .message-error { background: #593d3d; color: #f4d6d6; border: 1px solid #6b4a4a; }

        /* FOOTER */
        .footer { width: 100%; min-height: 330px; padding: 55px 68px 40px; background: #4b4b4b; color: #aaa; }
        .footer-language { display: flex; align-items: center; gap: 12px; margin-bottom: 56px; font-size: 14px; }
        .footer-language .active-language { color: #eee; }
        .footer-divider { width: 1px; height: 28px; background: #aaa; }
        .footer-copy { margin-bottom: 50px; font-size: 14px; }
        .footer-copy strong { color: #eee; font-weight: 500; }
        .footer-links { display: flex; align-items: center; gap: 14px; font-size: 14px; }
        .footer-link-divider { width: 1px; height: 28px; background: #999; }
        .footer-links a:hover { color: #eee; }

        /* DELETE MODAL */
        .delete-modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9000;
            display: none; align-items: center; justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity .2s;
        }
        .delete-modal-overlay.show { display: flex; opacity: 1; }

        .delete-modal {
            background: #3a3a3a; border: 1px solid #555; border-radius: 14px;
            padding: 28px; width: 100%; max-width: 400px;
            text-align: center;
            transform: scale(0.95);
            transition: transform .2s;
        }
        .delete-modal-overlay.show .delete-modal { transform: scale(1); }

        .delete-modal-icon {
            width: 52px; height: 52px; border-radius: 50%;
            background: rgba(217, 83, 79, 0.15); color: #d9534f;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .delete-modal-icon svg { width: 26px; height: 26px; }

        .delete-modal-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; color: #f5f5f5; }
        .delete-modal-subtitle { font-size: 12px; color: #999; margin-bottom: 22px; line-height: 1.5; }

        .delete-modal-actions { display: flex; gap: 10px; justify-content: center; }
        .delete-modal-btn {
            min-width: 110px; height: 40px; padding: 0 20px;
            border-radius: 22px; font-size: 12px; font-weight: 700;
            transition: background .15s, transform .15s;
        }
        .delete-modal-btn.cancel { background: #4a4a4a; color: #e5e5e5; }
        .delete-modal-btn.cancel:hover { background: #555; }
        .delete-modal-btn.confirm { background: #d9534f; color: #fff; }
        .delete-modal-btn.confirm:hover { background: #e35d59; transform: translateY(-1px); }
        .delete-modal-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        /* RESPONSIVE */
        @media (max-width: 1100px) {
            .upload-area { grid-template-columns: 1fr; }
            .main { padding-right: 25px; }
            .song-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 700px) {
            .layout { flex-direction: column; }
            .sidebar { position: relative; top: auto; margin: 15px; width: calc(100% - 30px); height: auto; min-height: auto; }
            .main { margin-left: 0; padding: 15px 15px 130px; }
            .upload-area { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; gap: 8px; }
            .footer { padding: 40px 25px; }
            .footer-links { flex-wrap: wrap; }
            .music-player { width: calc(100% - 30px); padding: 0 15px; gap: 10px; }
            .player-info { display: none; }
            .song-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>


<body>

<div class="page">

    <div class="layout">

        {{-- SIDEBAR --}}
        @include('partials.sidebar')


        {{-- MAIN --}}

        <main class="main">
            <div class="content">

                @if(session('success'))
                    <div class="message message-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="message message-error">
                        <ul style="padding-left: 18px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h1 class="page-title">Upload Your Song!</h1>


                <section class="uploaded-section" id="uploadedSection">

                    <div class="uploaded-title">
                        Uploaded Song
                        <span class="uploaded-count" id="uploadedCount">({{ $songs->count() }})</span>
                    </div>

                    @if($songs->count() > 0)

                        <div class="song-grid {{ $songs->count() > 4 ? 'hidden-songs' : '' }}" id="songGrid">

                            @foreach($songs as $index => $song)

                                <div class="song-card {{ $index >= 4 ? 'extra' : '' }}" data-song-id="{{ $song->id }}">

                                    <div class="song-cover">

                                        @if($song->cover_path)
                                            <img src="{{ asset('storage/' . $song->cover_path) }}" alt="{{ $song->title }}">
                                        @else
                                            <div class="song-cover-placeholder">♫</div>
                                        @endif

                                        <button
                                            type="button"
                                            class="delete-btn"
                                            data-song-id="{{ $song->id }}"
                                            data-song-title="{{ $song->title }}"
                                            title="Delete song"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>

                                    </div>

                                    <div class="song-title">{{ $song->title }}</div>
                                    <div class="song-artist">{{ $song->artist }}</div>

                                </div>

                            @endforeach

                        </div>

                        @if($songs->count() > 4)
                            <button type="button" class="show-more-songs" id="showMoreSongsBtn">
                                <span id="showMoreSongsText">Show More ({{ $songs->count() - 4 }})</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" id="showMoreSongsChevron">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                        @endif

                    @else

                        <div class="empty-songs">
                            Belum ada lagu. Klik <strong>Upload +</strong> di bawah buat nambahin lagu.
                        </div>

                    @endif

                </section>


                <div class="upload-toggle">
                    <span>Upload</span>
                    <button type="button" id="uploadToggle" class="upload-toggle-button" aria-label="Open upload form">+</button>
                </div>


                <div id="uploadArea" class="upload-area">

                    <form id="uploadForm" action="{{ route('creator.store') }}" method="POST" enctype="multipart/form-data" class="upload-form" data-turbo="false">

                        @csrf

                        <div class="form-row">
                            <div class="form-label">Insert Picture</div>
                            <div>
                                <input type="file" name="cover" id="coverInput" accept="image/png,image/jpeg,image/webp" hidden>
                                <label for="coverInput" class="file-button">+</label>
                                <div id="coverName" class="file-name"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-label">Insert Music</div>
                            <div>
                                <input type="file" name="audio" id="audioInput" accept=".mp3,.wav,.ogg,.m4a" hidden required>
                                <label for="audioInput" class="file-button">+</label>
                                <div id="audioName" class="file-name"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="title" class="form-label">Name</label>
                            <input type="text" name="title" id="title" class="text-input" maxlength="255" required>
                        </div>

                        <div class="form-row">
                            <label for="genre" class="form-label">Genre</label>
                            <select name="genre" id="genre" class="genre-select" required>
                                <option value="" disabled selected>Select Genre</option>
                                <option value="Pop">Pop</option>
                                <option value="Rock">Rock</option>
                                <option value="Hip Hop">Hip Hop</option>
                                <option value="Rap">Rap</option>
                                <option value="R&B">R&amp;B</option>
                                <option value="Soul">Soul</option>
                                <option value="Funk">Funk</option>
                                <option value="Jazz">Jazz</option>
                                <option value="Blues">Blues</option>
                                <option value="Classical">Classical</option>
                                <option value="Country">Country</option>
                                <option value="Folk">Folk</option>
                                <option value="Reggae">Reggae</option>
                                <option value="Gospel">Gospel</option>
                                <option value="Electronic">Electronic</option>
                                <option value="EDM">EDM</option>
                                <option value="House">House</option>
                                <option value="Techno">Techno</option>
                                <option value="Trance">Trance</option>
                                <option value="Dubstep">Dubstep</option>
                                <option value="Drum &amp; Bass">Drum &amp; Bass</option>
                                <option value="Ambient">Ambient</option>
                                <option value="Lo-fi">Lo-fi</option>
                                <option value="Metal">Metal</option>
                                <option value="Punk">Punk</option>
                                <option value="Alternative">Alternative</option>
                                <option value="Indie">Indie</option>
                                <option value="K-Pop">K-Pop</option>
                                <option value="J-Pop">J-Pop</option>
                                <option value="C-Pop">C-Pop</option>
                                <option value="Anime">Anime</option>
                                <option value="Soundtrack">Soundtrack</option>
                                <option value="Instrumental">Instrumental</option>
                                <option value="Acoustic">Acoustic</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <label for="artist" class="form-label">Artist</label>
                            <input type="text" name="artist" id="artist" class="text-input artist-input" value="{{ old('artist') }}" maxlength="255" placeholder="Enter artist name" required>
                        </div>

                        <div class="form-row">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="text-input description-input" maxlength="2000"></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="save-draft">Save Draft</button>
                        </div>

                    </form>


                    <div id="agreementBox" class="agreement">

                        <div class="agreement-logo">〽</div>
                        <div class="agreement-title">Wavo Creator Agreement</div>
                        <div class="agreement-subtitle">WAVO CREATOR &amp; MUSIC DISTRIBUTION TERMS</div>
                        <div class="agreement-date">Last updated: October 2026</div>

                        <div class="agreement-scroll">

                            <div class="agreement-section">
                                <div class="agreement-section-title">1. Grant of Rights &amp; Ownership</div>
                                <div class="agreement-section-text">
                                    You retain 100% full ownership of your Master Recordings and Composition Rights.
                                    By uploading to Wavo, you grant Wavo Interactive, a non-exclusive, worldwide,
                                    royalty-bearing license to stream, store, index, and promote your audio content
                                    across our platform and partner networks.
                                </div>
                            </div>

                            <div class="agreement-section">
                                <div class="agreement-section-title">2. Originality &amp; Sample Clearance Warranty</div>
                                <div class="agreement-section-text">
                                    You expressly warrant that you are the sole owner or authorized licensee of all
                                    audio content, cover artwork, lyrics, and metadata submitted. All third-party
                                    samples, beats, or interpolations MUST be fully cleared before publishing.
                                    Wavo is not liable for unauthorized sample usage.
                                </div>
                            </div>

                            <div class="agreement-section">
                                <div class="agreement-section-title">3. Monetization &amp; Royalty Distribution</div>
                                <div class="agreement-section-text">
                                    Earnings generated from legitimate user streams will be calculated based on Wavo's
                                    monthly revenue pool. Payments will be disbursed according to your chosen payout
                                    threshold. Wavo reserves the right to withhold payments if fraudulent activity is
                                    detected.
                                </div>
                            </div>

                            <div class="agreement-section">
                                <div class="agreement-section-title">4. Anti-Fraud &amp; Artificial Stream Policy</div>
                                <div class="agreement-section-text">
                                    The use of bots, click-farms, stream-boosters, or any automated method to inflate
                                    play counts is strictly prohibited. Detection of artificial manipulation will result
                                    in immediate track takedown, balance forfeiture, and permanent account termination.
                                </div>
                            </div>

                            <div class="agreement-section">
                                <div class="agreement-section-title">5. Content Moderation &amp; Takedown Rights</div>
                                <div class="agreement-section-text">
                                    Wavo reserves the right to review, flag, or remove any content that violates
                                    third-party copyrights, contains hate speech, incites violence, or fails to meet
                                    our audio quality standards without prior notice.
                                </div>
                            </div>

                            <div class="agreement-section">
                                <div class="agreement-section-title">6. Indemnification</div>
                                <div class="agreement-section-text">
                                    You agree to indemnify and hold harmless Wavo Interactive, its affiliates, and
                                    employees from any legal claims, damages, or legal fees arising from a breach of
                                    this Agreement or copyright infringement claims made by third parties.
                                </div>
                            </div>

                        </div>

                        <label class="agreement-check">
                            <input type="checkbox" id="agreementCheckbox" required>
                            <span>I confirm that I own all rights to this track and agree to Wavo's Creator Terms.</span>
                        </label>

                        <button type="button" id="showMoreButton" class="show-more">Show More...</button>

                        <div class="publish-wrapper">
                            <button type="submit" form="uploadForm" id="publishButton" class="publish-button" disabled>
                                Publish Music
                            </button>
                        </div>

                    </div>

                </div>

            </div>
        </main>

    </div>


    <footer class="footer">
        <div class="footer-language">
            <span class="active-language">Indonesia</span>
            <span class="footer-divider"></span>
            <span>Language English</span>
        </div>
        <div class="footer-copy">
            Copyright © 2026 <strong>Wavo Interactive.</strong> All rights reserved.
        </div>
        <div class="footer-links">
            <a href="#">Internet Service Terms</a>
            <span class="footer-link-divider"></span>
            <a href="#">Wavo Music &amp; Privacy</a>
            <span class="footer-link-divider"></span>
            <a href="#">Feedback</a>
            <span class="footer-link-divider"></span>
            <a href="#">Support</a>
        </div>
    </footer>

</div>



{{-- DELETE MODAL --}}

<div class="delete-modal-overlay" id="deleteModalOverlay">

    <div class="delete-modal">

        <div class="delete-modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>

        <div class="delete-modal-title">Are you sure want to delete this song?</div>

        <div class="delete-modal-subtitle" id="deleteModalSongTitle"></div>

        <div class="delete-modal-actions">
            <button type="button" class="delete-modal-btn cancel" id="deleteCancelBtn">Cancel</button>
            <button type="button" class="delete-modal-btn confirm" id="deleteConfirmBtn">Confirm</button>
        </div>

    </div>

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



<audio id="globalAudio" preload="none" data-turbo-permanent></audio>



<script>

    function initCreatorPage() {

        const uploadToggle = document.getElementById('uploadToggle');
        const uploadArea = document.getElementById('uploadArea');
        const showMoreButton = document.getElementById('showMoreButton');
        const agreementBox = document.getElementById('agreementBox');
        const agreementCheckbox = document.getElementById('agreementCheckbox');
        const publishButton = document.getElementById('publishButton');
        const uploadForm = document.getElementById('uploadForm');
        const audioInput = document.getElementById('audioInput');
        const coverInput = document.getElementById('coverInput');
        const audioName = document.getElementById('audioName');
        const coverName = document.getElementById('coverName');

        if (!uploadToggle) return;
        if (uploadToggle.dataset.bound === '1') return;
        uploadToggle.dataset.bound = '1';

        uploadToggle.addEventListener('click', function () {
            const isOpen = uploadArea.classList.toggle('show');
            uploadToggle.innerHTML = isOpen ? '⌄' : '+';
        });

        showMoreButton.addEventListener('click', function () {
            const expanded = agreementBox.classList.toggle('expanded');
            if (expanded) {
                showMoreButton.textContent = 'Show Less...';
            } else {
                showMoreButton.textContent = 'Show More...';
                const scrollArea = agreementBox.querySelector('.agreement-scroll');
                if (scrollArea) scrollArea.scrollTop = 0;
            }
        });

        agreementCheckbox.addEventListener('change', function () {
            publishButton.disabled = !this.checked;
        });

        audioInput.addEventListener('change', function () {
            audioName.textContent = this.files.length > 0 ? this.files[0].name : '';
        });

        coverInput.addEventListener('change', function () {
            coverName.textContent = this.files.length > 0 ? this.files[0].name : '';
        });

        publishButton.addEventListener('click', function (event) {
            event.preventDefault();
            if (!agreementCheckbox.checked) return;

            let agreementField = uploadForm.querySelector('input[name="agreement"]');
            if (!agreementField) {
                agreementField = document.createElement('input');
                agreementField.type = 'hidden';
                agreementField.name = 'agreement';
                uploadForm.appendChild(agreementField);
            }
            agreementField.value = '1';
            uploadForm.submit();
        });


        const showMoreSongsBtn = document.getElementById('showMoreSongsBtn');
        const showMoreSongsText = document.getElementById('showMoreSongsText');
        const showMoreSongsChevron = document.getElementById('showMoreSongsChevron');
        const songGrid = document.getElementById('songGrid');

        if (showMoreSongsBtn && songGrid && showMoreSongsBtn.dataset.bound !== '1') {

            showMoreSongsBtn.dataset.bound = '1';

            showMoreSongsBtn.addEventListener('click', function () {

                const totalSongs = songGrid.querySelectorAll('.song-card').length;
                const visibleCount = 4;
                const isHidden = songGrid.classList.contains('hidden-songs');

                if (isHidden) {
                    songGrid.classList.remove('hidden-songs');
                    showMoreSongsText.textContent = 'Show Less';
                    if (showMoreSongsChevron) {
                        showMoreSongsChevron.style.transform = 'rotate(180deg)';
                    }
                } else {
                    songGrid.classList.add('hidden-songs');
                    showMoreSongsText.textContent = 'Show More (' + (totalSongs - visibleCount) + ')';
                    if (showMoreSongsChevron) {
                        showMoreSongsChevron.style.transform = 'rotate(0deg)';
                    }
                    document.getElementById('uploadedSection')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        }
    }


    function initDeleteModal() {

        const overlay = document.getElementById('deleteModalOverlay');
        const songTitleEl = document.getElementById('deleteModalSongTitle');
        const cancelBtn = document.getElementById('deleteCancelBtn');
        const confirmBtn = document.getElementById('deleteConfirmBtn');

        if (!overlay) return;
        if (overlay.dataset.bound === '1') return;
        overlay.dataset.bound = '1';

        let songToDeleteId = null;

        document.addEventListener('click', function (e) {

            const btn = e.target.closest('.delete-btn');
            if (!btn) return;

            e.stopPropagation();
            e.preventDefault();

            songToDeleteId = btn.dataset.songId;
            songTitleEl.textContent = btn.dataset.songTitle || '';

            overlay.classList.add('show');
        });

        cancelBtn.addEventListener('click', function () {
            overlay.classList.remove('show');
            songToDeleteId = null;
        });

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                overlay.classList.remove('show');
                songToDeleteId = null;
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('show')) {
                overlay.classList.remove('show');
                songToDeleteId = null;
            }
        });

        confirmBtn.addEventListener('click', function () {

            if (!songToDeleteId) return;

            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Deleting...';

            const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

            fetch('/creator/' + songToDeleteId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {

                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Confirm';

                if (!data.success) {
                    alert('Gagal hapus lagu.');
                    return;
                }

                const card = document.querySelector('.song-card[data-song-id="' + songToDeleteId + '"]');
                if (card) {
                    card.style.transition = 'opacity .25s, transform .25s';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';

                    setTimeout(() => {

                        card.remove();

                        const remaining = document.querySelectorAll('.song-card').length;
                        const countEl = document.getElementById('uploadedCount');
                        if (countEl) countEl.textContent = '(' + remaining + ')';

                        const showBtn = document.getElementById('showMoreSongsBtn');
                        const grid = document.getElementById('songGrid');

                        if (showBtn && grid && remaining <= 4) {
                            showBtn.remove();
                            grid.classList.remove('hidden-songs');
                            grid.querySelectorAll('.song-card').forEach(c => c.classList.remove('extra'));
                        }

                        if (remaining === 0) {
                            if (grid) grid.remove();
                            const section = document.getElementById('uploadedSection');
                            if (section && !section.querySelector('.empty-songs')) {
                                const empty = document.createElement('div');
                                empty.className = 'empty-songs';
                                empty.innerHTML = 'Belum ada lagu. Klik <strong>Upload +</strong> di bawah buat nambahin lagu.';
                                section.appendChild(empty);
                            }
                        }

                    }, 250);
                }

                overlay.classList.remove('show');
                songToDeleteId = null;
            })
            .catch(() => {
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Confirm';
                alert('Gagal hapus lagu.');
            });
        });
    }


    function initGlobalPlayer() {

        if (window.__globalPlayerInited) return;
        window.__globalPlayerInited = true;

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
            const rows = Array.from(document.querySelectorAll('.song-row'));
            const cards = Array.from(document.querySelectorAll('.song-card'));
            songItems = rows.length > 0 ? rows : cards;
        }

        function formatTime(seconds) {
            if (!seconds || isNaN(seconds)) return '0:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return m + ':' + (s < 10 ? '0' : '') + s;
        }

        function playItem(index, autoPlay = true) {
            if (index < 0 || index >= songItems.length) return;
            currentIndex = index;
            const item = songItems[index];

            if (item.classList.contains('song-row')) {
                songItems.forEach(r => r.classList.remove('playing'));
                item.classList.add('playing');
            }

            playerTitle.textContent = item.dataset.title || 'Unknown';
            playerArtist.textContent = item.dataset.artist || 'Unknown';

            const cover = item.dataset.cover || '';
            playerCover.innerHTML = cover ? `<img src="${cover}" alt="">` : '〽';

            globalAudio.src = item.dataset.audio;
            globalAudio.load();
            if (autoPlay) globalAudio.play().catch(() => {});
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
    }


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


    document.addEventListener('DOMContentLoaded', () => {
        initCreatorPage();
        initDeleteModal();
        initGlobalPlayer();
        updateSidebarActive();
    });

    document.addEventListener('turbo:load', () => {
        initCreatorPage();
        initDeleteModal();
        initGlobalPlayer();
        updateSidebarActive();
    });

</script>

</body>
</html>