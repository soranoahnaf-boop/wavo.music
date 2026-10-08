{{--
|--------------------------------------------------------------------------
| WAVO GLOBAL MUSIC PLAYER
|--------------------------------------------------------------------------
| SATU-SATUNYA SUMBER MUSIC PLAYER
|
| Semua halaman cukup:
| @include('partials.music-player')
|
| Song card cukup mempunyai:
| data-song-id
| data-title
| data-artist
| data-cover
| data-audio
|--------------------------------------------------------------------------
--}}

<style>
    /* =========================================================
       GLOBAL MUSIC PLAYER
    ========================================================= */

    .music-player {
        position: fixed;
        left: 50%;
        bottom: 24px;
        transform: translateX(-50%);

        width: 620px;
        max-width: calc(100% - 48px);
        height: 68px;

        background: #2a2a2a;
        border: 1px solid #555;
        border-radius: 34px;

        z-index: 2000;

        display: flex;
        align-items: center;

        padding: 0 22px;
        gap: 18px;

        box-shadow: 0 10px 30px rgba(0, 0, 0, .5);
    }

    /* =========================================================
       LEFT INFO
    ========================================================= */

    .player-info {
        display: flex;
        align-items: center;
        gap: 10px;

        width: 160px;
        min-width: 0;
        flex-shrink: 0;

        cursor: pointer;
        border-radius: 10px;
    }

    .player-info:focus-visible {
        outline: 2px solid #c5a45c;
        outline-offset: 3px;
    }

    .player-cover {
        width: 38px;
        height: 38px;

        border-radius: 8px;

        background: #1a1a1a;
        border: 1px solid #3d3d3d;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #c5a45c;
        font-size: 18px;

        overflow: hidden;
        flex-shrink: 0;
    }

    .player-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .player-info-text {
        min-width: 0;
        flex: 1;
    }

    .player-title {
        color: #f5f5f5;

        font-size: 12px;
        font-weight: 600;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .player-artist {
        color: #888;

        font-size: 10px;

        margin-top: 2px;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* =========================================================
       CENTER
    ========================================================= */

    .player-controls {
        flex: 1;
        min-width: 0;

        display: flex;
        flex-direction: column;
        align-items: center;

        gap: 6px;
    }

    .player-buttons {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: 16px;
    }

    .player-buttons button,
    .player-right button {
        border: none;
        background: transparent;

        color: #888;

        padding: 4px;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;

        transition:
            color .15s,
            transform .15s;
    }

    .player-buttons button:hover,
    .player-right button:hover {
        color: #f5f5f5;
    }

    .player-buttons button.active {
        color: #c5a45c;
    }

    /* PLAY BUTTON */

    .player-buttons button.play {
        width: 34px;
        height: 34px;

        padding: 0;

        border-radius: 50%;

        background: #c5a45c;
        color: #1a1a1a;

        transition:
            transform .15s,
            background .15s;
    }

    .player-buttons button.play:hover {
        background: #d4b76a;
        transform: scale(1.08);
    }

    /* =========================================================
       PROGRESS
    ========================================================= */

    .player-progress {
        width: 100%;
        max-width: 320px;

        display: flex;
        align-items: center;

        gap: 8px;
    }

    .player-progress span {
        color: #777;

        font-size: 10px;

        flex-shrink: 0;

        font-variant-numeric: tabular-nums;
    }

    .progress-bar {
        position: relative;

        flex: 1;

        height: 3px;

        background: #444;

        border-radius: 2px;

        cursor: pointer;

        overflow: hidden;
    }

    .progress-fill {
        width: 0%;
        height: 100%;

        background: #c5a45c;

        border-radius: 2px;

        transition: width .1s linear;
    }

    /* =========================================================
       RIGHT
    ========================================================= */

    .player-right {
        width: 90px;
        min-width: 90px;

        display: flex;
        align-items: center;
        justify-content: flex-end;

        flex-shrink: 0;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 700px) {

        .music-player {
            width: calc(100% - 30px);

            padding: 0 15px;

            gap: 10px;
        }

        .player-info {
            display: none;
        }

        .player-right {
            width: auto;
            min-width: auto;
        }

        .player-buttons {
            gap: 10px;
        }

        .player-progress {
            max-width: 260px;
        }
    }
</style>


{{-- =============================================================
     PLAYER UI
================================================================= --}}

<div
    class="music-player"
    id="musicPlayer"
    data-turbo-permanent
>

    {{-- SONG INFORMATION --}}

    <div
        class="player-info"
        id="playerInfo"
        role="button"
        tabindex="0"
        title="Open full-screen player"
        aria-label="Open full-screen player"
    >

        <div
            class="player-cover"
            id="playerCover"
        >
            〽
        </div>

        <div class="player-info-text">

            <div
                class="player-title"
                id="playerTitle"
            >
                Not Playing
            </div>

            <div
                class="player-artist"
                id="playerArtist"
            >
                Select a song
            </div>

        </div>

    </div>


    {{-- CONTROLS --}}

    <div class="player-controls">

        <div class="player-buttons">

            {{-- SHUFFLE --}}

            <button
                type="button"
                id="shuffleBtn"
                title="Shuffle"
                aria-label="Shuffle"
            >
                <svg
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <polyline points="16 3 21 3 21 8"></polyline>
                    <line x1="4" y1="20" x2="21" y2="3"></line>
                    <polyline points="21 16 21 21 16 21"></polyline>
                    <line x1="15" y1="15" x2="21" y2="21"></line>
                    <line x1="4" y1="4" x2="9" y2="9"></line>
                </svg>
            </button>


            {{-- PREVIOUS --}}

            <button
                type="button"
                id="prevBtn"
                title="Previous"
                aria-label="Previous"
            >
                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <polygon points="19 20 9 12 19 4 19 20"></polygon>

                    <line
                        x1="5"
                        y1="19"
                        x2="5"
                        y2="5"
                        stroke="currentColor"
                        stroke-width="2"
                    ></line>
                </svg>
            </button>


            {{-- PLAY / PAUSE --}}

            <button
                type="button"
                class="play"
                id="playPauseBtn"
                title="Play"
                aria-label="Play"
            >

                <svg
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >

                    <polygon
                        id="playIcon"
                        points="6 4 20 12 6 20 6 4"
                    ></polygon>

                </svg>

            </button>


            {{-- NEXT --}}

            <button
                type="button"
                id="nextBtn"
                title="Next"
                aria-label="Next"
            >
                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <polygon points="5 4 15 12 5 20 5 4"></polygon>

                    <line
                        x1="19"
                        y1="5"
                        x2="19"
                        y2="19"
                        stroke="currentColor"
                        stroke-width="2"
                    ></line>
                </svg>
            </button>


            {{-- REPEAT --}}

            <button
                type="button"
                id="repeatBtn"
                title="Repeat"
                aria-label="Repeat"
            >
                <svg
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <polyline points="17 1 21 5 17 9"></polyline>

                    <path
                        d="M3 11V9a4 4 0 0 1 4-4h14"
                    ></path>

                    <polyline points="7 23 3 19 7 15"></polyline>

                    <path
                        d="M21 13v2a4 4 0 0 1-4 4H3"
                    ></path>
                </svg>
            </button>

        </div>


        {{-- PROGRESS --}}

        <div class="player-progress">

            <span id="currentTime">
                0:00
            </span>

            <div
                class="progress-bar"
                id="progressBar"
                title="Seek"
            >
                <div
                    class="progress-fill"
                    id="progressFill"
                ></div>
            </div>

            <span id="totalTime">
                0:00
            </span>

        </div>

    </div>


    {{-- MUTE --}}

    <div class="player-right">

        <button
            type="button"
            id="muteBtn"
            title="Mute"
            aria-label="Mute"
        >

            <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >

                <polygon
                    points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"
                    fill="currentColor"
                ></polygon>

                <path
                    d="M15.54 8.46a5 5 0 0 1 0 7.07"
                ></path>

                <path
                    d="M19.07 4.93a10 10 0 0 1 0 14.14"
                ></path>

            </svg>

        </button>

    </div>

</div>


{{-- =============================================================
     ONE AND ONLY AUDIO ELEMENT
================================================================= --}}

<audio
    id="globalAudio"
    preload="none"
    data-turbo-permanent
></audio>


{{-- Full-screen player (opens from the cover / title above) --}}
@include('partials.full-player')


<script>
/*
|--------------------------------------------------------------------------
| WAVO GLOBAL MUSIC PLAYER CONTROLLER
|--------------------------------------------------------------------------
|
| SATU-SATUNYA sumber logic / state music player untuk SEMUA halaman,
| termasuk Radio (Radio hanya mengendalikan player ini lewat
| window.WavoMusicPlayer; tidak ada <audio> lain).
|
| Aturan arsitektur:
|
|  1. Script ini DIEKSEKUSI ULANG oleh Turbo pada setiap navigasi karena
|     berada di dalam <body>. Controller hanya dibuat SEKALI. Eksekusi
|     berikutnya hanya memanggil rebind() (ringan dan idempoten).
|
|  2. State "mana lagu yang diputar" dan "play/pause" TIDAK disimpan di
|     variabel script. Sumber kebenarannya adalah elemen <audio id="globalAudio">
|     yang permanen (paused, currentTime, src, dataset.songId). Controller
|     hanya menyimpan state yang bukan milik <audio>: shuffle, repeat,
|     muted, volume dan queue.
|
|  3. Semua tombol player dan semua song card/row ditangani lewat EVENT
|     DELEGATION di document, didaftarkan satu kali. Tidak ada listener
|     yang menempel ke elemen halaman, jadi tidak ada listener ganda.
|
|  4. Listener <audio> (play, pause, timeupdate, ...) didaftarkan satu kali
|     per elemen audio (dilacak lewat boundAudio).
|
|  5. Navigasi Turbo hanya boleh:
|        a) refreshSongs()  -> memperbarui daftar song di halaman baru
|        b) syncUI()        -> menggambar ulang UI dari state audio (read-only)
|     Navigasi TIDAK PERNAH memanggil audio.load(), audio.src = ...,
|     audio.currentTime = ..., audio.play() ataupun audio.pause().
|
*/
(function () {

    'use strict';

    /*
    |--------------------------------------------------------------------------
    | WAVO CONFIG
    |--------------------------------------------------------------------------
    |
    | Dijalankan pada setiap eksekusi script supaya nilai (csrf, auth) selalu
    | mengikuti halaman terbaru. Tidak menyentuh state player.
    |
    */

    window.WAVO = window.WAVO || {};
    window.WAVO.urls = window.WAVO.urls || {};

    window.WAVO.csrf = @json(csrf_token());
    window.WAVO.auth = @json(Auth::check());

    window.WAVO.urls.played = @json(url('/home'));

    /*
     * Halaman Home mendefinisikan window.WAVO dengan pola
     * "window.WAVO = window.WAVO || {...}" SETELAH partial ini berjalan,
     * sehingga key di bawah ini tidak akan pernah terisi dari Home.
     * Lengkapi di sini tanpa menimpa nilai yang sudah ada.
     */
    window.WAVO.urls.favorite =
        window.WAVO.urls.favorite || @json(url('/home'));

    window.WAVO.urls.playlistStore =
        window.WAVO.urls.playlistStore || @json(route('playlist.store'));

    window.WAVO.urls.playlistAddSong =
        window.WAVO.urls.playlistAddSong || @json(url('/playlist'));


    /*
    |--------------------------------------------------------------------------
    | JANGAN BUAT CONTROLLER KEDUA
    |--------------------------------------------------------------------------
    */

    if (window.__WAVO_GLOBAL_PLAYER_INITIALIZED && window.WavoMusicPlayer) {

        /*
         * Turbo mengeksekusi ulang script ini. Cukup pastikan listener
         * <audio> terpasang pada elemen audio yang sekarang dan UI sinkron.
         */
        window.WavoMusicPlayer.rebind();

        return;
    }

    window.__WAVO_GLOBAL_PLAYER_INITIALIZED = true;


    /*
    |--------------------------------------------------------------------------
    | KONSTANTA
    |--------------------------------------------------------------------------
    */

    const SONG_SELECTOR =
        '.song-card[data-audio], .song-row[data-audio]';

    const PLAY_POINTS  = '6 4 20 12 6 20 6 4';
    const PAUSE_POINTS = '6 4 14 4 14 20 6 20';

    const NOW_PLAYING_BARS =
        '<svg viewBox="0 0 24 24" fill="currentColor">' +
        '<rect x="2" y="10" width="2" height="4" rx="1"/>' +
        '<rect x="6" y="6" width="2" height="12" rx="1"/>' +
        '<rect x="10" y="3" width="2" height="18" rx="1"/>' +
        '<rect x="14" y="7" width="2" height="10" rx="1"/>' +
        '<rect x="18" y="10" width="2" height="4" rx="1"/>' +
        '</svg>';


    /*
    |--------------------------------------------------------------------------
    | STATE (hanya yang bukan milik <audio>)
    |--------------------------------------------------------------------------
    */

    const state = {

        shuffle: false,

        repeat: false,

        muted: false,

        volume: 1,

        /*
         * Lagu yang sedang dimuat: {id, url, title, artist, cover}
         */
        current: null,

        /*
         * Daftar lagu terakhir yang memuat lagu aktif. Dipakai Next/Previous
         * dan "ended" walaupun user sudah pindah ke halaman yang tidak
         * memuat lagu itu.
         */
        queue: []
    };

    /*
     * Daftar elemen song di halaman yang sedang tampil.
     */
    let songs = [];

    /*
     * <audio> yang sudah dipasangi listener.
     */
    let boundAudio = null;

    let refreshTimer = null;


    /*
    |--------------------------------------------------------------------------
    | HELPER DOM
    |--------------------------------------------------------------------------
    |
    | Elemen selalu di-resolve saat dibutuhkan, tidak di-cache, supaya tidak
    | pernah memegang referensi elemen yang sudah lepas dari document.
    |
    */

    function $(id) {
        return document.getElementById(id);
    }

    function getAudio() {

        const element = $('globalAudio');

        /*
         * Saat Turbo mengganti <body>, script inline berjalan SEBELUM elemen
         * permanen dikembalikan. Pada saat itu id "globalAudio" sempat dimiliki
         * <meta> placeholder milik Turbo. Hanya terima <audio> yang asli.
         */
        return element && element.tagName === 'AUDIO'
            ? element
            : null;
    }

    function formatTime(seconds) {

        if (!seconds || !Number.isFinite(seconds)) {
            return '0:00';
        }

        const minutes = Math.floor(seconds / 60);
        const rest = Math.floor(seconds % 60);

        return minutes + ':' + (rest < 10 ? '0' : '') + rest;
    }

    function safePlay(audio) {

        if (!audio) {
            return;
        }

        const promise = audio.play();

        if (promise && typeof promise.catch === 'function') {
            promise.catch(function () {});
        }
    }

    function hasSong(audio) {
        return !!(audio && audio.getAttribute('src'));
    }

    function currentId() {

        const audio = getAudio();

        if (audio && audio.dataset.songId) {
            return String(audio.dataset.songId);
        }

        return state.current && state.current.id
            ? String(state.current.id)
            : '';
    }

    function describe(element) {

        return {
            id: element.dataset.songId || '',
            url: element.dataset.audio || '',
            title: element.dataset.title || 'Unknown',
            artist: element.dataset.artist || 'Unknown',
            cover: element.dataset.cover || ''
        };
    }

    function sameSong(audio, song) {

        if (!audio || !song || !hasSong(audio)) {
            return false;
        }

        if (song.id && audio.dataset.songId) {
            return String(song.id) === String(audio.dataset.songId);
        }

        try {
            return audio.src === new URL(song.url, document.baseURI).href;
        } catch (error) {
            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER UI (tidak pernah mengubah audio)
    |--------------------------------------------------------------------------
    */

    function renderPlayState() {

        const audio = getAudio();
        const icon = $('playIcon');
        const button = $('playPauseBtn');

        if (!icon || !button) {
            return;
        }

        const playing = !!audio && !audio.paused && !audio.ended;
        const label = playing ? 'Pause' : 'Play';

        const points = playing ? PAUSE_POINTS : PLAY_POINTS;

        if (icon.getAttribute('points') !== points) {
            icon.setAttribute('points', points);
        }

        if (button.title !== label) {
            button.title = label;
        }

        if (button.getAttribute('aria-label') !== label) {
            button.setAttribute('aria-label', label);
        }
    }

    function renderCover(coverEl, url) {

        if ((coverEl.dataset.src || '') === url) {
            return;
        }

        coverEl.dataset.src = url;

        coverEl.textContent = '';

        if (url) {

            const img = document.createElement('img');

            img.src = url;
            img.alt = '';

            coverEl.appendChild(img);

        } else {

            coverEl.textContent = '〽';
        }
    }

    function renderMeta(meta) {

        const title = $('playerTitle');
        const artist = $('playerArtist');
        const cover = $('playerCover');

        if (!title || !artist || !cover) {
            return;
        }

        const titleText = meta ? meta.title : 'Not Playing';
        const artistText = meta ? meta.artist : 'Select a song';

        if (title.textContent !== titleText) {
            title.textContent = titleText;
        }

        if (artist.textContent !== artistText) {
            artist.textContent = artistText;
        }

        renderCover(cover, meta ? meta.cover : '');
    }

    function renderProgress() {

        const audio = getAudio();
        const fill = $('progressFill');
        const now = $('currentTime');
        const total = $('totalTime');

        if (!audio || !fill || !now || !total) {
            return;
        }

        if (!hasSong(audio)) {

            fill.style.width = '0%';
            now.textContent = '0:00';
            total.textContent = '0:00';

            return;
        }

        const duration = audio.duration;

        if (Number.isFinite(duration) && duration > 0) {

            fill.style.width =
                ((audio.currentTime / duration) * 100) + '%';

            total.textContent = formatTime(duration);

        } else {

            fill.style.width = '0%';
            total.textContent = '0:00';
        }

        now.textContent = formatTime(audio.currentTime);
    }

    function renderToggles() {

        const shuffleBtn = $('shuffleBtn');
        const repeatBtn = $('repeatBtn');
        const muteBtn = $('muteBtn');

        if (shuffleBtn) {
            shuffleBtn.classList.toggle('active', state.shuffle);
        }

        if (repeatBtn) {
            repeatBtn.classList.toggle('active', state.repeat);
        }

        if (muteBtn) {
            muteBtn.style.color = state.muted ? '#d9534f' : '';
        }
    }

    /*
     * Tandai baris lagu aktif pada halaman (.song-row.playing).
     * Murni tampilan, tidak menyentuh audio.
     */
    function renderNowPlayingMarkers() {

        const id = currentId();
        const audio = getAudio();
        const active = !!id && hasSong(audio);

        document
            .querySelectorAll('.song-row[data-audio]')
            .forEach(function (row) {

                const isActive =
                    active &&
                    String(row.dataset.songId || '') === id;

                row.classList.toggle('playing', isActive);

                /*
                 * Opsional: halaman yang menandai
                 * data-now-playing-icon="bars" pada container list-nya
                 * mengganti nomor urut dengan ikon equalizer.
                 */
                const holder =
                    row.closest('[data-now-playing-icon="bars"]');

                const number = row.querySelector('.song-number');

                if (!holder || !number) {
                    return;
                }

                if (number.dataset.originalNumber === undefined) {
                    number.dataset.originalNumber =
                        number.textContent.trim();
                }

                const hasBars = !!number.querySelector('svg');

                if (isActive && !hasBars) {
                    number.innerHTML = NOW_PLAYING_BARS;
                }

                if (!isActive && hasBars) {
                    number.textContent = number.dataset.originalNumber;
                }
            });
    }

    /*
     * Gambar ulang SEMUA UI dari state yang ada.
     * Aman dipanggil kapan saja, termasuk setelah navigasi Turbo:
     * hanya membaca audio.paused / currentTime / duration.
     */
    function syncUI() {

        const audio = getAudio();

        if (!audio) {
            return;
        }

        if (state.muted !== audio.muted) {
            audio.muted = state.muted;
        }

        let meta = null;

        if (hasSong(audio)) {

            if (state.current) {

                meta = state.current;

            } else if (audio.dataset.songId || audio.dataset.title) {

                meta = {
                    id: audio.dataset.songId || '',
                    url: audio.getAttribute('src'),
                    title: audio.dataset.title || 'Unknown',
                    artist: audio.dataset.artist || 'Unknown',
                    cover: audio.dataset.cover || ''
                };
            }
        }

        renderMeta(meta);
        renderPlayState();
        renderProgress();
        renderToggles();
        renderNowPlayingMarkers();
    }


    /*
    |--------------------------------------------------------------------------
    | 1. REFRESH SONG LIST
    |--------------------------------------------------------------------------
    |
    | Hanya membaca DOM halaman. Tidak memuat lagu, tidak play, tidak pause,
    | tidak mengubah currentTime.
    |
    */

    function refreshSongs() {

        songs = Array.from(document.querySelectorAll(SONG_SELECTOR));

        const id = currentId();

        if (id && songs.some(function (el) {
            return String(el.dataset.songId || '') === id;
        })) {

            /*
             * Halaman ini memuat lagu aktif: jadikan daftar halaman ini
             * sebagai queue.
             */
            state.queue = songs.map(describe);
        }

        renderNowPlayingMarkers();
    }

    function scheduleRefresh() {

        if (refreshTimer) {
            return;
        }

        refreshTimer = setTimeout(function () {

            refreshTimer = null;

            refreshSongs();

        }, 60);
    }


    /*
    |--------------------------------------------------------------------------
    | 2. LOAD / PLAY CURRENT SONG
    |--------------------------------------------------------------------------
    |
    | Hanya dipanggil dari aksi user: klik lagu, Play, Next, Previous,
    | atau event 'ended'. Tidak pernah dari navigasi.
    |
    */

    function saveRecentlyPlayed(songId) {

        if (!window.WAVO.auth || !songId) {
            return;
        }

        const baseUrl = window.WAVO.urls.played || '/home';

        fetch(
            baseUrl + '/' + encodeURIComponent(songId) + '/played',
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.WAVO.csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }
        ).catch(function () {});
    }

    function loadSong(song, autoPlay) {

        const audio = getAudio();

        if (!audio || !song || !song.url) {
            return;
        }

        state.current = song;

        audio.dataset.songId = song.id || '';
        audio.dataset.title = song.title || '';
        audio.dataset.artist = song.artist || '';
        audio.dataset.cover = song.cover || '';

        audio.pause();
        audio.src = song.url;
        audio.load();

        renderMeta(song);
        renderProgress();

        saveRecentlyPlayed(song.id);

        /*
         * Queue = daftar halaman ini bila memuat lagu tersebut.
         */
        refreshSongs();

        if (!state.queue.some(function (item) {
            return String(item.id) === String(song.id);
        })) {
            state.queue = [song];
        }

        renderNowPlayingMarkers();

        if (autoPlay !== false) {
            safePlay(audio);
        }
    }

    /*
     * Dipanggil saat user memilih lagu.
     */
    function playSong(song) {

        const audio = getAudio();

        if (!audio || !song || !song.url) {
            return;
        }

        /*
         * Lagu yang sama: kalau paused -> lanjutkan dari posisi terakhir.
         * Kalau sedang main -> jangan restart.
         */
        if (sameSong(audio, song)) {

            if (audio.paused) {
                safePlay(audio);
            }

            return;
        }

        loadSong(song, true);
    }

    function playElement(element) {

        if (!element || !element.dataset) {
            return;
        }

        refreshSongs();

        playSong(describe(element));
    }

    function queueIndex() {

        const id = currentId();

        if (!id) {
            return -1;
        }

        return state.queue.findIndex(function (item) {
            return String(item.id) === id;
        });
    }

    function ensureQueue() {

        if (state.queue.length === 0) {

            refreshSongs();

            state.queue = songs.map(describe);
        }

        return state.queue.length > 0;
    }

    function playNext() {

        if (!ensureQueue()) {
            return;
        }

        const index = queueIndex();
        let nextIndex;

        if (state.shuffle) {

            if (state.queue.length === 1) {

                nextIndex = 0;

            } else {

                do {
                    nextIndex =
                        Math.floor(Math.random() * state.queue.length);
                } while (nextIndex === index);
            }

        } else {

            nextIndex = index + 1;

            if (nextIndex >= state.queue.length) {
                nextIndex = 0;
            }
        }

        loadSong(state.queue[nextIndex], true);
    }

    function playPrevious() {

        if (!ensureQueue()) {
            return;
        }

        let previousIndex = queueIndex() - 1;

        if (previousIndex < 0) {
            previousIndex = state.queue.length - 1;
        }

        loadSong(state.queue[previousIndex], true);
    }

    function togglePlay() {

        const audio = getAudio();

        if (!audio) {
            return;
        }

        /*
         * Belum ada lagu sama sekali -> mulai dari lagu pertama halaman.
         * (Hanya terjadi bila memang tidak ada lagu yang dimuat. Lagu aktif
         * yang tidak ada di halaman ini TIDAK dianggap "tidak ada lagu".)
         */
        if (!hasSong(audio)) {

            if (ensureQueue()) {
                loadSong(state.queue[0], true);
            }

            return;
        }

        if (audio.paused) {
            safePlay(audio);
        } else {
            audio.pause();
        }
    }

    function seekTo(percent) {

        const audio = getAudio();

        if (
            !audio ||
            !Number.isFinite(audio.duration) ||
            audio.duration <= 0
        ) {
            return;
        }

        percent = Math.max(0, Math.min(1, percent));

        audio.currentTime = percent * audio.duration;

        renderProgress();
    }

    function stopPlayer() {

        const audio = getAudio();

        if (!audio) {
            return;
        }

        audio.pause();
        audio.removeAttribute('src');
        audio.load();

        audio.dataset.songId = '';
        audio.dataset.title = '';
        audio.dataset.artist = '';
        audio.dataset.cover = '';

        state.current = null;
        state.queue = [];

        syncUI();
    }


    /*
    |--------------------------------------------------------------------------
    | EVENT <audio> (terpasang SEKALI per elemen audio)
    |--------------------------------------------------------------------------
    */

    function onAudioPlay(event) {

        /*
         * Jangan ada dua audio bermain bersamaan.
         */
        document.querySelectorAll('audio').forEach(function (other) {

            if (other !== event.target && !other.paused) {
                other.pause();
            }
        });

        renderPlayState();
    }

    function onAudioPause() {
        renderPlayState();
    }

    function onAudioTimeUpdate() {
        renderProgress();
    }

    function onAudioMeta() {
        renderProgress();
    }

    function onAudioEnded() {

        const audio = getAudio();

        if (state.repeat) {

            if (audio) {
                audio.currentTime = 0;
                safePlay(audio);
            }

            return;
        }

        playNext();
    }

    const AUDIO_EVENTS = [
        ['play', onAudioPlay],
        ['pause', onAudioPause],
        ['timeupdate', onAudioTimeUpdate],
        ['loadedmetadata', onAudioMeta],
        ['durationchange', onAudioMeta],
        ['emptied', onAudioMeta],
        ['ended', onAudioEnded]
    ];

    function bindAudio() {

        const audio = getAudio();

        if (!audio || audio === boundAudio) {
            return;
        }

        if (boundAudio) {

            AUDIO_EVENTS.forEach(function (pair) {
                boundAudio.removeEventListener(pair[0], pair[1]);
            });
        }

        /*
         * Elemen audio BARU (mis. halaman tanpa player permanen
         * menyebabkan elemen lama hilang). Mulai dari state kosong.
         */
        if (boundAudio && !hasSong(audio)) {
            state.current = null;
            state.queue = [];
        }

        AUDIO_EVENTS.forEach(function (pair) {
            audio.addEventListener(pair[0], pair[1]);
        });

        boundAudio = audio;

        audio.volume = state.volume;
        audio.muted = state.muted;
    }


    /*
    |--------------------------------------------------------------------------
    | EVENT UI (event delegation, terpasang SEKALI di document)
    |--------------------------------------------------------------------------
    */

    function ignoredSongClick(target, element) {

        if (
            target.closest(
                '.favorite-button, .more-button, .delete-btn'
            )
        ) {
            return true;
        }

        /*
         * Baris playlist: tombol apa pun di dalam baris tidak memutar lagu.
         */
        if (
            element.classList.contains('song-row') &&
            target.closest('button')
        ) {
            return true;
        }

        return false;
    }

    document.addEventListener('click', function (event) {

        const target = event.target;

        if (!target || !target.closest) {
            return;
        }

        /*
         * Kontrol player.
         */
        if (target.closest('#playPauseBtn')) {
            togglePlay();
            return;
        }

        if (target.closest('#nextBtn')) {
            playNext();
            return;
        }

        if (target.closest('#prevBtn')) {
            playPrevious();
            return;
        }

        if (target.closest('#shuffleBtn')) {
            state.shuffle = !state.shuffle;
            renderToggles();
            return;
        }

        if (target.closest('#repeatBtn')) {
            state.repeat = !state.repeat;
            renderToggles();
            return;
        }

        if (target.closest('#muteBtn')) {

            state.muted = !state.muted;

            const audio = getAudio();

            if (audio) {
                audio.muted = state.muted;
            }

            renderToggles();

            return;
        }

        const bar = target.closest('#progressBar');

        if (bar) {

            const rect = bar.getBoundingClientRect();

            if (rect.width > 0) {
                seekTo((event.clientX - rect.left) / rect.width);
            }

            return;
        }

        /*
         * Tombol "Play" besar di halaman Playlist / Favorites:
         * putar lagu pertama di halaman.
         */
        if (target.closest('#heroPlayBtn')) {

            refreshSongs();

            if (songs.length > 0) {
                playElement(songs[0]);
            }

            return;
        }

        /*
         * Song card / song row.
         */
        const element = target.closest(SONG_SELECTOR);

        if (!element || ignoredSongClick(target, element)) {
            return;
        }

        playElement(element);
    });

    document.addEventListener('keydown', function (event) {

        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }

        const target = event.target;

        if (!target || !target.closest) {
            return;
        }

        const element = target.closest(SONG_SELECTOR);

        if (!element) {
            return;
        }

        /*
         * Jangan ganggu kontrol interaktif di dalam card.
         */
        if (
            target.closest(
                'button, a, input, textarea, select, [contenteditable]'
            )
        ) {
            return;
        }

        event.preventDefault();

        playElement(element);
    });

    /*
     * Satu audio saja yang boleh bermain. Kalau <audio> lain di halaman
     * (misalnya <audio controls> pada hasil pencarian) mulai bermain,
     * hentikan player global. Event media tidak bubble, jadi pakai capture.
     */
    document.addEventListener('play', function (event) {

        const source = event.target;

        if (
            !source ||
            source.tagName !== 'AUDIO' ||
            source.id === 'globalAudio' ||
            source.id === 'radioAudio'
        ) {
            return;
        }

        const audio = getAudio();

        if (audio && !audio.paused) {
            audio.pause();
        }

    }, true);


    /*
    |--------------------------------------------------------------------------
    | TURBO
    |--------------------------------------------------------------------------
    |
    | Navigasi = refresh daftar song + gambar ulang UI. Titik.
    |
    */

    function onPageChanged() {

        bindAudio();

        refreshSongs();

        syncUI();
    }

    document.addEventListener('turbo:render', onPageChanged);
    document.addEventListener('turbo:load', onPageChanged);

    /*
     * Observer ke documentElement (bukan body) karena Turbo mengganti
     * elemen <body>. Perubahan di dalam #musicPlayer diabaikan supaya
     * update progress tidak memicu refresh berulang.
     */
    new MutationObserver(function (mutations) {

        for (let i = 0; i < mutations.length; i++) {

            const target = mutations[i].target;

            const element =
                target && target.nodeType === 1
                    ? target
                    : target && target.parentElement;

            if (element && element.closest('#musicPlayer')) {
                continue;
            }

            scheduleRefresh();

            return;
        }

    }).observe(document.documentElement, {
        childList: true,
        subtree: true
    });


    /*
    |--------------------------------------------------------------------------
    | PUBLIC API
    |--------------------------------------------------------------------------
    */

    window.WavoMusicPlayer = {

        playElement: playElement,

        refresh: refreshSongs,

        next: playNext,

        previous: playPrevious,

        toggle: togglePlay,

        play: function () {

            const audio = getAudio();

            if (audio && hasSong(audio)) {
                safePlay(audio);
            }
        },

        pause: function () {

            const audio = getAudio();

            if (audio) {
                audio.pause();
            }
        },

        stop: stopPlayer,

        seek: function (seconds) {

            const audio = getAudio();

            if (audio && Number.isFinite(seconds)) {
                audio.currentTime = Math.max(0, seconds);
            }
        },

        setVolume: function (value) {

            const audio = getAudio();

            state.volume = Math.max(0, Math.min(1, Number(value) || 0));

            if (audio) {
                audio.volume = state.volume;
            }
        },

        getCurrentSongId: function () {
            return currentId() || null;
        },

        isPlaying: function () {

            const audio = getAudio();

            return !!audio && !audio.paused && !audio.ended;
        },

        getState: function () {

            const audio = getAudio();

            return {
                songId: currentId() || null,
                title: audio ? (audio.dataset.title || null) : null,
                artist: audio ? (audio.dataset.artist || null) : null,
                cover: audio ? (audio.dataset.cover || null) : null,
                paused: audio ? audio.paused : true,
                currentTime: audio ? audio.currentTime : 0,
                duration: audio ? audio.duration : 0,
                shuffle: state.shuffle,
                repeat: state.repeat,
                muted: state.muted,
                volume: state.volume
            };
        },

        toggleShuffle: function () {

            state.shuffle = !state.shuffle;

            renderToggles();

            return state.shuffle;
        },

        toggleRepeat: function () {

            state.repeat = !state.repeat;

            renderToggles();

            return state.repeat;
        },

        toggleMute: function () {

            const audio = getAudio();

            state.muted = !state.muted;

            if (audio) {
                audio.muted = state.muted;
            }

            renderToggles();

            return state.muted;
        },

        getQueue: function () {
            return state.queue.map(function (item) {
                return Object.assign({}, item);
            });
        },

        playById: function (id) {

            const item = state.queue.find(function (entry) {
                return String(entry.id) === String(id);
            });

            if (item) {
                loadSong(item, true);
            }
        },

        rebind: onPageChanged,

        syncUI: syncUI
    };


    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */

    onPageChanged();

})();
</script>
