```blade
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

    <div class="player-info">

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


<script>

(function () {

    /*
    |--------------------------------------------------------------------------
    | JANGAN INITIALIZE PLAYER BERKALI-KALI
    |--------------------------------------------------------------------------
    */

    if (window.__WAVO_GLOBAL_PLAYER_INITIALIZED) {
        return;
    }

    window.__WAVO_GLOBAL_PLAYER_INITIALIZED = true;


    /*
    |--------------------------------------------------------------------------
    | WAVO GLOBAL CONFIG
    |--------------------------------------------------------------------------
    */

    window.WAVO = window.WAVO || {};

    window.WAVO.csrf =
        window.WAVO.csrf ||
        @json(csrf_token());

    window.WAVO.auth =
        typeof window.WAVO.auth !== 'undefined'
            ? window.WAVO.auth
            : @json(Auth::check());


    window.WAVO.urls =
        window.WAVO.urls || {};

    window.WAVO.urls.played =
        window.WAVO.urls.played ||
        @json(url('/home'));


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const audio =
        document.getElementById('globalAudio');

    const player =
        document.getElementById('musicPlayer');

    const title =
        document.getElementById('playerTitle');

    const artist =
        document.getElementById('playerArtist');

    const cover =
        document.getElementById('playerCover');

    const playPause =
        document.getElementById('playPauseBtn');

    const playIcon =
        document.getElementById('playIcon');

    const prev =
        document.getElementById('prevBtn');

    const next =
        document.getElementById('nextBtn');

    const shuffleBtn =
        document.getElementById('shuffleBtn');

    const repeatBtn =
        document.getElementById('repeatBtn');

    const muteBtn =
        document.getElementById('muteBtn');

    const progressBar =
        document.getElementById('progressBar');

    const progressFill =
        document.getElementById('progressFill');

    const currentTime =
        document.getElementById('currentTime');

    const totalTime =
        document.getElementById('totalTime');


    if (
        !audio ||
        !player ||
        !title ||
        !artist ||
        !cover ||
        !playPause ||
        !prev ||
        !next ||
        !shuffleBtn ||
        !repeatBtn ||
        !muteBtn ||
        !progressBar ||
        !progressFill ||
        !currentTime ||
        !totalTime
    ) {
        console.warn(
            'Wavo Music Player: element tidak lengkap.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    let songs = [];

    let currentSongIndex = -1;

    let shuffle = false;

    let repeat = false;

    let isMuted = false;


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    function formatTime(seconds) {

        if (
            !seconds ||
            !Number.isFinite(seconds)
        ) {
            return '0:00';
        }

        const minutes =
            Math.floor(seconds / 60);

        const secondsPart =
            Math.floor(seconds % 60);

        return (
            minutes +
            ':' +
            (secondsPart < 10 ? '0' : '') +
            secondsPart
        );
    }


    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA SONG CARD
    |--------------------------------------------------------------------------
    */

    function refreshSongs() {

        songs =
            Array.from(
                document.querySelectorAll(
                    '.song-card[data-audio]'
                )
            );


        /*
         * Kalau lagu yang sedang dimainkan
         * masih ada, cari index barunya.
         */

        if (currentSongIndex !== -1) {

            const currentId =
                audio.dataset.songId;

            if (currentId) {

                const newIndex =
                    songs.findIndex(
                        card =>
                            String(
                                card.dataset.songId
                            ) ===
                            String(currentId)
                    );

                currentSongIndex =
                    newIndex;

            }

        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PLAYER INFO
    |--------------------------------------------------------------------------
    */

    function updatePlayerInfo(card) {

        if (!card) return;


        title.textContent =
            card.dataset.title ||
            'Unknown';

        artist.textContent =
            card.dataset.artist ||
            'Unknown';


        const coverUrl =
            card.dataset.cover ||
            '';


        if (coverUrl) {

            cover.innerHTML =
                '<img src="' +
                escapeHtml(coverUrl) +
                '" alt="">';

        } else {

            cover.textContent =
                '〽';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | SEND RECENTLY PLAYED
    |--------------------------------------------------------------------------
    */

    function saveRecentlyPlayed(songId) {

        if (
            !window.WAVO.auth ||
            !songId
        ) {
            return;
        }


        const baseUrl =
            window.WAVO.urls.played ||
            '/home';


        fetch(
            baseUrl +
            '/' +
            encodeURIComponent(songId) +
            '/played',
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        window.WAVO.csrf,

                    'Accept':
                        'application/json',

                    'Content-Type':
                        'application/json'
                }
            }
        ).catch(() => {});
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD SONG
    |--------------------------------------------------------------------------
    */

    function loadSong(
        index,
        autoPlay = true
    ) {

        refreshSongs();


        if (
            index < 0 ||
            index >= songs.length
        ) {
            return;
        }


        const card =
            songs[index];

        const audioUrl =
            card.dataset.audio;


        if (!audioUrl) {
            return;
        }


        currentSongIndex =
            index;


        updatePlayerInfo(card);


        /*
         * Simpan ID lagu pada audio element
         * supaya tetap diketahui walaupun halaman
         * berpindah.
         */

        audio.dataset.songId =
            card.dataset.songId || '';


        audio.dataset.title =
            card.dataset.title || '';

        audio.dataset.artist =
            card.dataset.artist || '';

        audio.dataset.cover =
            card.dataset.cover || '';


        /*
         * Jangan pakai src lama.
         */

        audio.pause();

        audio.src =
            audioUrl;

        audio.load();


        progressFill.style.width =
            '0%';

        currentTime.textContent =
            '0:00';

        totalTime.textContent =
            '0:00';


        saveRecentlyPlayed(
            card.dataset.songId
        );


        if (autoPlay) {

            const playPromise =
                audio.play();

            if (
                playPromise &&
                typeof playPromise.catch ===
                'function'
            ) {

                playPromise.catch(() => {});

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | PLAY CARD
    |--------------------------------------------------------------------------
    |
    | Ini fungsi yang dipakai Creator,
    | Home, Search, Playlist, dll.
    |
    */

    function playElement(card) {

        if (!card) {
            return;
        }


        refreshSongs();


        const index =
            songs.indexOf(card);


        /*
         * Kalau card berasal dari halaman
         * yang baru saja berubah dan belum masuk
         * daftar songs, cari berdasarkan ID.
         */

        let finalIndex =
            index;


        if (finalIndex === -1) {

            const id =
                card.dataset.songId;


            finalIndex =
                songs.findIndex(
                    item =>
                        String(
                            item.dataset.songId
                        ) ===
                        String(id)
                );

        }


        if (finalIndex !== -1) {

            /*
             * Klik lagu yang sama:
             * kalau sedang pause -> lanjut.
             * kalau sedang main -> jangan restart.
             */

            const sameSong =
                String(
                    audio.dataset.songId || ''
                ) ===
                String(
                    card.dataset.songId || ''
                );


            if (sameSong) {

                if (audio.paused) {

                    const promise =
                        audio.play();

                    if (
                        promise &&
                        promise.catch
                    ) {
                        promise.catch(() => {});
                    }

                }

                return;
            }


            loadSong(
                finalIndex,
                true
            );

            return;
        }


        /*
         * Fallback:
         * card tidak ditemukan dalam daftar.
         * Tetap bisa diputar langsung.
         */

        updatePlayerInfo(card);

        audio.dataset.songId =
            card.dataset.songId || '';

        audio.dataset.title =
            card.dataset.title || '';

        audio.dataset.artist =
            card.dataset.artist || '';

        audio.dataset.cover =
            card.dataset.cover || '';

        audio.src =
            card.dataset.audio || '';

        audio.load();

        saveRecentlyPlayed(
            card.dataset.songId
        );

        const promise =
            audio.play();

        if (
            promise &&
            promise.catch
        ) {
            promise.catch(() => {});
        }

    }


    /*
    |--------------------------------------------------------------------------
    | PLAY / PAUSE
    |--------------------------------------------------------------------------
    */

    function togglePlay() {

        refreshSongs();


        if (currentSongIndex === -1) {

            if (songs.length > 0) {

                loadSong(
                    0,
                    true
                );

            }

            return;
        }


        if (audio.paused) {

            const promise =
                audio.play();

            if (
                promise &&
                promise.catch
            ) {
                promise.catch(() => {});
            }

        } else {

            audio.pause();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | NEXT
    |--------------------------------------------------------------------------
    */

    function playNext() {

        refreshSongs();


        if (!songs.length) {
            return;
        }


        let nextIndex;


        if (shuffle) {

            if (songs.length === 1) {

                nextIndex = 0;

            } else {

                do {

                    nextIndex =
                        Math.floor(
                            Math.random() *
                            songs.length
                        );

                } while (
                    nextIndex ===
                    currentSongIndex
                );

            }

        } else {

            nextIndex =
                currentSongIndex + 1;


            if (
                nextIndex >=
                songs.length
            ) {

                nextIndex = 0;

            }

        }


        loadSong(
            nextIndex,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PREVIOUS
    |--------------------------------------------------------------------------
    */

    function playPrevious() {

        refreshSongs();


        if (!songs.length) {
            return;
        }


        let previousIndex =
            currentSongIndex - 1;


        if (
            previousIndex < 0
        ) {

            previousIndex =
                songs.length - 1;

        }


        loadSong(
            previousIndex,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PLAY EVENT
    |--------------------------------------------------------------------------
    */

    audio.addEventListener(
        'play',
        function () {

            playIcon.setAttribute(
                'points',
                '6 4 14 4 14 20 6 20'
            );

            playPause.title =
                'Pause';

            playPause.setAttribute(
                'aria-label',
                'Pause'
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PAUSE EVENT
    |--------------------------------------------------------------------------
    */

    audio.addEventListener(
        'pause',
        function () {

            playIcon.setAttribute(
                'points',
                '6 4 20 12 6 20 6 4'
            );

            playPause.title =
                'Play';

            playPause.setAttribute(
                'aria-label',
                'Play'
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | TIME UPDATE
    |--------------------------------------------------------------------------
    */

    audio.addEventListener(
        'timeupdate',
        function () {

            if (
                !Number.isFinite(
                    audio.duration
                ) ||
                audio.duration <= 0
            ) {
                return;
            }


            const percent =
                (
                    audio.currentTime /
                    audio.duration
                ) * 100;


            progressFill.style.width =
                percent + '%';


            currentTime.textContent =
                formatTime(
                    audio.currentTime
                );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | METADATA LOADED
    |--------------------------------------------------------------------------
    */

    audio.addEventListener(
        'loadedmetadata',
        function () {

            totalTime.textContent =
                formatTime(
                    audio.duration
                );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SONG ENDED
    |--------------------------------------------------------------------------
    */

    audio.addEventListener(
        'ended',
        function () {

            if (repeat) {

                audio.currentTime =
                    0;


                const promise =
                    audio.play();


                if (
                    promise &&
                    promise.catch
                ) {
                    promise.catch(() => {});
                }


                return;
            }


            playNext();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PLAY BUTTON
    |--------------------------------------------------------------------------
    */

    playPause.addEventListener(
        'click',
        function () {

            togglePlay();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | NEXT BUTTON
    |--------------------------------------------------------------------------
    */

    next.addEventListener(
        'click',
        function () {

            playNext();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PREVIOUS BUTTON
    |--------------------------------------------------------------------------
    */

    prev.addEventListener(
        'click',
        function () {

            playPrevious();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SHUFFLE
    |--------------------------------------------------------------------------
    */

    shuffleBtn.addEventListener(
        'click',
        function () {

            shuffle =
                !shuffle;


            shuffleBtn.classList.toggle(
                'active',
                shuffle
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REPEAT
    |--------------------------------------------------------------------------
    */

    repeatBtn.addEventListener(
        'click',
        function () {

            repeat =
                !repeat;


            repeatBtn.classList.toggle(
                'active',
                repeat
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | MUTE
    |--------------------------------------------------------------------------
    */

    muteBtn.addEventListener(
        'click',
        function () {

            isMuted =
                !isMuted;


            audio.muted =
                isMuted;


            muteBtn.style.color =
                isMuted
                    ? '#d9534f'
                    : '';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SEEK
    |--------------------------------------------------------------------------
    */

    progressBar.addEventListener(
        'click',
        function (event) {

            if (
                !Number.isFinite(
                    audio.duration
                ) ||
                audio.duration <= 0
            ) {
                return;
            }


            const rect =
                progressBar.getBoundingClientRect();


            let percent =
                (
                    event.clientX -
                    rect.left
                ) /
                rect.width;


            percent =
                Math.max(
                    0,
                    Math.min(
                        1,
                        percent
                    )
                );


            audio.currentTime =
                percent *
                audio.duration;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLICK SEMUA SONG CARD
    |--------------------------------------------------------------------------
    |
    | Jadi Creator tidak perlu membuat player sendiri.
    | Home juga tidak perlu membuat player sendiri.
    | Search juga sama.
    |
    */

    document.addEventListener(
        'click',
        function (event) {

            const card =
                event.target.closest(
                    '.song-card[data-audio]'
                );


            if (!card) {
                return;
            }


            /*
             * Favorite jangan ikut play.
             */

            if (
                event.target.closest(
                    '.favorite-button'
                )
            ) {
                return;
            }


            /*
             * More button jangan ikut play.
             */

            if (
                event.target.closest(
                    '.more-button'
                )
            ) {
                return;
            }


            /*
             * Delete button jangan ikut play.
             */

            if (
                event.target.closest(
                    '.delete-btn'
                )
            ) {
                return;
            }


            playElement(card);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | KEYBOARD SUPPORT
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key !== 'Enter' &&
                event.key !== ' '
            ) {
                return;
            }


            const card =
                event.target.closest(
                    '.song-card[data-audio]'
                );


            if (!card) {
                return;
            }


            /*
             * Jangan ganggu input / textarea / select.
             */

            const tag =
                event.target.tagName;


            if (
                tag === 'INPUT' ||
                tag === 'TEXTAREA' ||
                tag === 'SELECT'
            ) {
                return;
            }


            event.preventDefault();


            playElement(card);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PUBLIC API
    |--------------------------------------------------------------------------
    |
    | Creator dan halaman lain bisa menggunakan:
    |
    | window.WavoMusicPlayer.playElement(card)
    | window.WavoMusicPlayer.refresh()
    | window.WavoMusicPlayer.next()
    | window.WavoMusicPlayer.previous()
    | window.WavoMusicPlayer.stop()
    | window.WavoMusicPlayer.getCurrentSongId()
    |
    */

    window.WavoMusicPlayer = {

        playElement: playElement,

        refresh: refreshSongs,

        next: playNext,

        previous: playPrevious,

        stop: function () {

            audio.pause();

            audio.currentTime =
                0;

            audio.removeAttribute(
                'src'
            );

            audio.load();


            currentSongIndex =
                -1;


            audio.dataset.songId =
                '';


            title.textContent =
                'Not Playing';

            artist.textContent =
                'Select a song';

            cover.textContent =
                '〽';


            progressFill.style.width =
                '0%';

            currentTime.textContent =
                '0:00';

            totalTime.textContent =
                '0:00';

        },

        getCurrentSongId: function () {

            return (
                audio.dataset.songId ||
                null
            );

        },

        isPlaying: function () {

            return (
                !audio.paused &&
                !audio.ended
            );

        },

        pause: function () {

            audio.pause();

        },

        play: function () {

            const promise =
                audio.play();

            if (
                promise &&
                promise.catch
            ) {
                promise.catch(() => {});
            }

        }

    };


    /*
    |--------------------------------------------------------------------------
    | INITIAL REFRESH
    |--------------------------------------------------------------------------
    */

    refreshSongs();


    /*
    |--------------------------------------------------------------------------
    | TURBO PAGE LOAD
    |--------------------------------------------------------------------------
    |
    | Saat pindah Home -> Creator -> Search,
    | daftar card diperbarui.
    |
    */

    document.addEventListener(
        'turbo:load',
        function () {

            refreshSongs();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | MUTATION OBSERVER
    |--------------------------------------------------------------------------
    |
    | Kalau card lagu muncul/hilang secara dinamis,
    | daftar player ikut diperbarui.
    |
    */

    const observer =
        new MutationObserver(
            function () {

                refreshSongs();

            }
        );


    observer.observe(
        document.body,
        {
            childList: true,
            subtree: true
        }
    );


})();
</script>
```
