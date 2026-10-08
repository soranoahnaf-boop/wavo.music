<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Radio - Wavo Music</title>

    {{-- Turbo: supaya pindah halaman tidak reload (audio global tetap hidup) --}}
    <script src="https://unpkg.com/@hotwired/turbo@8.0.0/dist/turbo.es2017-umd.js"></script>

    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    /*
     * Turbo menyimpan <style> halaman sebelumnya di <head>, jadi aturan
     * level-dokumen HARUS di-scope ke body.radio-body. Kalau tidak,
     * "overflow: hidden" ini ikut mematikan scroll di Home / Search / Songs
     * setelah user pindah dari Radio.
     */
    body.radio-body {
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: #202020;
        color: #fff;
        font-family: Arial, Helvetica, sans-serif;
    }

    /* Dipisah dari rule di atas supaya browser tanpa :has() tidak ikut gagal. */
    html:has(body.radio-body) {
        height: 100%;
        overflow: hidden;
    }

    /*
     * Radio punya kontrol sendiri yang mengendalikan global player.
     * Bar global tetap ada di DOM (diperlukan agar audio permanen), tapi
     * disembunyikan HANYA di halaman ini. Hapus rule ini kalau bar global
     * ingin ikut tampil di Radio.
     */
    body.radio-body .music-player {
        display: none;
    }

    button {
        border: none;
        background: none;
        color: inherit;
        cursor: pointer;
        font-family: inherit;
    }

    /* ==========================================================
       RADIO PAGE
    ========================================================== */

    .radio-page {
        position: relative;
        width: 100vw;
        height: 100dvh;
        min-height: 500px;
        overflow: hidden;
        background: #202020;
    }

    /* ==========================================================
       BACKGROUND
    ========================================================== */

    .radio-background {
    position: absolute;
    inset: -15%;
    overflow: hidden;
    background: #181818;
    filter: blur(55px);
    transform: scale(1.12);
    opacity: .95;
}

.radio-background::before,
.radio-background::after {
    content: "";
    position: absolute;
    width: 65vw;
    height: 65vw;
    border-radius: 50%;
    filter: blur(90px);
    opacity: .65;
    transition:
        background-color 1s ease,
        transform 12s ease-in-out;
}

.radio-background::before {
    left: -15%;
    top: -10%;
    background: var(--ambient-1, #c5a45c);
    animation: ambientBlobOne 14s ease-in-out infinite alternate;
}

.radio-background::after {
    right: -15%;
    bottom: -15%;
    background: var(--ambient-2, #8c7040);
    animation: ambientBlobTwo 17s ease-in-out infinite alternate;
}

.radio-page {
    --ambient-1: #c5a45c;
    --ambient-2: #8c7040;
    --ambient-3: #5f4d2e;
}

.radio-page::before {
    content: "";
    position: absolute;
    inset: -10%;
    z-index: 0;
    pointer-events: none;

    background:
        radial-gradient(
            circle at 50% 90%,
            var(--ambient-3),
            transparent 45%
        );

    filter: blur(80px);
    opacity: .7;

    animation: ambientCenter 20s ease-in-out infinite alternate;
}

@keyframes ambientBlobOne {
    0% {
        transform: translate3d(-5%, -5%, 0) scale(1);
    }

    50% {
        transform: translate3d(20%, 15%, 0) scale(1.15);
    }

    100% {
        transform: translate3d(5%, 30%, 0) scale(.95);
    }
}

@keyframes ambientBlobTwo {
    0% {
        transform: translate3d(5%, 10%, 0) scale(1);
    }

    50% {
        transform: translate3d(-20%, -15%, 0) scale(1.2);
    }

    100% {
        transform: translate3d(-5%, -30%, 0) scale(.9);
    }
}

@keyframes ambientCenter {
    0% {
        transform: translate3d(-5%, 5%, 0) scale(1);
    }

    50% {
        transform: translate3d(10%, -10%, 0) scale(1.2);
    }

    100% {
        transform: translate3d(-10%, -5%, 0) scale(1.05);
    }
}

/* Saat musik berhenti, gerakannya dibuat lebih lambat */
.radio-page:not(.is-playing) .radio-background::before {
    animation-duration: 30s;
}

.radio-page:not(.is-playing) .radio-background::after {
    animation-duration: 34s;
}

.radio-page:not(.is-playing)::before {
    animation-duration: 40s;
}

    /* ==========================================================
       BRAND
    ========================================================== */

    .radio-brand {
        position: absolute;

        top: clamp(20px, 4vh, 42px);
        left: clamp(20px, 4vw, 55px);

        display: flex;
        align-items: center;

        gap: clamp(9px, 1.2vw, 17px);

        z-index: 5;

        text-decoration: none;
    }

    .radio-brand-logo {
        color: #c5a45c;

        font-size: clamp(28px, 3vw, 42px);
        line-height: 1;

        transform: scaleX(1.15);
    }

    .radio-brand-text {
        color: #f4f4f4;

        font-size: clamp(20px, 2vw, 28px);
        font-weight: 600;
    }

    /* ==========================================================
       MAIN CONTENT
    ========================================================== */

    .radio-content {
        position: relative;

        width: 100%;
        height: 100%;

        display: flex;
        flex-direction: column;
        align-items: center;

        z-index: 3;
    }

    /* ==========================================================
       HEADER
    ========================================================== */

    .radio-header {
        margin-top: clamp(85px, 14vh, 135px);

        width: min(90vw, 700px);

        display: grid;
        grid-template-columns: 1fr auto 1fr;

        align-items: center;
    }

    .radio-header-button {
        width: clamp(32px, 3vw, 42px);
        height: clamp(32px, 3vw, 42px);

        display: flex;
        align-items: center;
        justify-content: center;

        color: #fff;

        transition:
            transform .15s ease,
            color .15s ease;
    }

    #previousButton {
        justify-self: end;
    }

    #nextButton {
        justify-self: start;
    }

    .radio-header-button:hover {
        color: #c5a45c;
        transform: scale(1.1);
    }

    .radio-header-button svg {
        width: clamp(22px, 2.2vw, 30px);
        height: clamp(22px, 2.2vw, 30px);
    }

    .radio-station {
        color: #f5f5f5;

        font-size: clamp(22px, 2.4vw, 34px);
        font-weight: 600;

        text-align: center;

        white-space: nowrap;
    }

    /* ==========================================================
       COVER
    ========================================================== */

    .radio-cover-wrapper {
        position: relative;

        /*
         * Ukuran cover mengikuti tinggi dan lebar layar.
         * Jadi tidak akan terlalu besar di layar kecil.
         */
        width: min(
            35vh,
            35vw,
            350px
        );

        aspect-ratio: 1 / 1;

        margin-top: clamp(
            35px,
            7vh,
            70px
        );

        flex-shrink: 1;
    }

    .radio-cover-glow {
        position: absolute;
        inset: 0;

        border-radius: clamp(20px, 3vw, 34px);

        background: #c5a45c;

        filter: blur(
            clamp(30px, 5vw, 55px)
        );

        opacity: .18;

        transform: scale(.85);
    }

    .radio-cover {
        position: relative;

        width: 100%;
        height: 100%;

        border-radius: clamp(
            18px,
            3vw,
            34px
        );

        overflow: hidden;

        background: #707070;

        display: flex;
        align-items: center;
        justify-content: center;

        box-shadow:
            0 25px 70px rgba(0, 0, 0, .38);

        z-index: 2;
    }

    .radio-cover img {
        width: 100%;
        height: 100%;

        object-fit: cover;

        display: block;
    }

    .radio-cover-placeholder {
        color: #c5a45c;

        font-size: clamp(
            60px,
            9vw,
            105px
        );

        line-height: 1;
    }

    /* ==========================================================
       SONG INFO
    ========================================================== */

    .radio-song-info {
        margin-top: clamp(
            10px,
            2vh,
            20px
        );

        width: min(80vw, 500px);

        min-height: clamp(35px, 5vh, 44px);

        text-align: center;

        overflow: hidden;
    }

    .radio-song-title {
        color: #fff;

        font-size: clamp(
            14px,
            1.2vw,
            17px
        );

        font-weight: 600;

        margin-bottom: 5px;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .radio-song-artist {
        color: #aaa;

        font-size: clamp(
            10px,
            .9vw,
            12px
        );

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ==========================================================
       BOTTOM CONTROLS
    ========================================================== */

    .radio-controls {
        position: absolute;

        left: 50%;
        bottom: clamp(
            70px,
            11vh,
            115px
        );

        transform: translateX(-50%);

        display: flex;
        align-items: center;
        justify-content: center;

        width: min(90vw, 500px);
    }

    .radio-control-group {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: clamp(
            25px,
            6vw,
            75px
        );
    }

    .radio-control-button {
        width: clamp(
            32px,
            3.2vw,
            42px
        );

        height: clamp(
            32px,
            3.2vw,
            42px
        );

        display: flex;
        align-items: center;
        justify-content: center;

        color: #f5f5f5;

        transition:
            color .15s ease,
            transform .15s ease;
    }

    .radio-control-button:hover {
        color: #c5a45c;
        transform: scale(1.08);
    }

    .radio-control-button.active {
        color: #c5a45c;
    }

    .radio-control-button svg {
        width: clamp(
            21px,
            2.2vw,
            27px
        );

        height: clamp(
            21px,
            2.2vw,
            27px
        );
    }

    /* ==========================================================
       PLAY BUTTON
    ========================================================== */

    .radio-play {
        width: clamp(
            48px,
            5vw,
            64px
        );

        height: clamp(
            48px,
            5vw,
            64px
        );

        display: flex;
        align-items: center;
        justify-content: center;

        color: #fff;

        transition:
            transform .15s ease,
            color .15s ease;
    }

    .radio-play:hover {
        color: #c5a45c;
        transform: scale(1.08);
    }

    .radio-play svg {
        width: 100%;
        height: 100%;
    }

    /* ==========================================================
       VOLUME
    ========================================================== */

    .radio-volume {
        position: absolute;

        left: clamp(
            20px,
            10vw,
            190px
        );

        bottom: clamp(
            70px,
            11vh,
            116px
        );

        display: flex;
        align-items: center;

        z-index: 5;
    }

    .radio-volume-button {
        width: clamp(
            32px,
            3.2vw,
            42px
        );

        height: clamp(
            32px,
            3.2vw,
            42px
        );

        display: flex;
        align-items: center;
        justify-content: center;

        color: #fff;

        transition:
            color .15s ease,
            transform .15s ease;
    }

    .radio-volume-button:hover {
        color: #c5a45c;
        transform: scale(1.08);
    }

    .radio-volume-button svg {
        width: clamp(
            23px,
            2.5vw,
            31px
        );

        height: clamp(
            23px,
            2.5vw,
            31px
        );
    }

    /* ==========================================================
       QUEUE
    ========================================================== */

    .radio-queue {
        position: absolute;

        right: clamp(
            20px,
            10vw,
            190px
        );

        bottom: clamp(
            70px,
            11vh,
            116px
        );

        z-index: 5;
    }

    .radio-queue-button {
        width: clamp(
            36px,
            3.5vw,
            50px
        );

        height: clamp(
            36px,
            3.5vw,
            50px
        );

        display: flex;
        align-items: center;
        justify-content: center;

        color: #f5f5f5;

        transition:
            color .15s ease,
            transform .15s ease;
    }

    .radio-queue-button:hover {
        color: #c5a45c;
        transform: scale(1.08);
    }

    .radio-queue-button svg {
        width: clamp(
            30px,
            3.2vw,
            47px
        );

        height: clamp(
            30px,
            3.2vw,
            47px
        );
    }

    /* ==========================================================
       PROGRESS
    ========================================================== */

    .radio-progress-wrapper {
        position: absolute;

        left: 50%;

        bottom: clamp(
            30px,
            6vh,
            68px
        );

        transform: translateX(-50%);

        width: min(
            35vw,
            330px
        );

        min-width: 180px;

        display: flex;
        align-items: center;

        gap: 8px;

        opacity: .85;
    }

    .radio-time {
        width: 32px;

        flex-shrink: 0;

        color: #aaa;

        font-size: 10px;

        font-variant-numeric: tabular-nums;

        text-align: center;
    }

    .radio-progress {
        position: relative;

        flex: 1;

        height: 3px;

        background: rgba(
            255,
            255,
            255,
            .22
        );

        border-radius: 10px;

        overflow: hidden;

        cursor: pointer;
    }

    .radio-progress-fill {
        width: 0%;

        height: 100%;

        background: #c5a45c;

        border-radius: inherit;
    }

    /* ==========================================================
       QUEUE PANEL
    ========================================================== */

    .queue-panel {
        position: fixed;

        right: clamp(
            15px,
            3vw,
            35px
        );

        bottom: clamp(
            125px,
            18vh,
            175px
        );

        width: min(
            290px,
            calc(100vw - 30px)
        );

        max-height: min(
            420px,
            55vh
        );

        padding: 18px;

        background: rgba(
            38,
            38,
            38,
            .96
        );

        border: 1px solid #555;

        border-radius: 14px;

        box-shadow:
            0 20px 60px rgba(
                0,
                0,
                0,
                .55
            );

        z-index: 20;

        display: none;

        overflow-y: auto;
    }

    .queue-panel.show {
        display: block;
    }

    .queue-title {
        font-size: 14px;
        font-weight: 700;

        margin-bottom: 12px;
    }

    .radio-genre-select {
        width: 100%;
        margin-bottom: 10px;
        padding: 8px 10px;
        color: #f5f5f5;
        background: #303030;
        border: 1px solid #555;
        border-radius: 8px;
        font: inherit;
        font-size: 12px;
    }

    .queue-item {
        width: 100%;

        display: flex;
        align-items: center;

        gap: 10px;

        padding: 8px;

        border-radius: 8px;

        text-align: left;

        color: #ccc;
    }

    .queue-item[hidden] {
        display: none;
    }

    .queue-item:hover {
        background: #3b3b3b;
    }

    .queue-item.active {
        color: #c5a45c;
        background: #363636;
    }

    .queue-item-cover {
        width: 38px;
        height: 38px;

        flex-shrink: 0;

        border-radius: 6px;

        overflow: hidden;

        background: #555;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #c5a45c;
    }

    .queue-item-cover img {
        width: 100%;
        height: 100%;

        object-fit: cover;
    }

    .queue-item-info {
        min-width: 0;
    }

    .queue-item-title {
        font-size: 12px;
        font-weight: 600;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .queue-item-artist {
        margin-top: 3px;

        font-size: 10px;

        color: #888;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ==========================================================
       TABLET
    ========================================================== */

    @media (max-width: 900px) {

        .radio-cover-wrapper {
            width: min(
                38vh,
                42vw,
                320px
            );
        }

        .radio-volume {
            left: 5vw;
        }

        .radio-queue {
            right: 5vw;
        }

        .radio-progress-wrapper {
            width: min(
                45vw,
                300px
            );
        }
    }


    /* ==========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 600px) {

        .radio-brand {
            top: 20px;
            left: 20px;
        }

        .radio-header {
            margin-top: 85px;

            width: 85vw;
        }

        .radio-station {
            font-size: clamp(
                18px,
                5vw,
                24px
            );
        }

        .radio-cover-wrapper {
            width: min(
                55vw,
                34vh,
                250px
            );

            margin-top: 35px;
        }

        .radio-song-info {
            width: 70vw;
        }

        .radio-controls {
            bottom: 90px;

            width: 70vw;
        }

        .radio-control-group {
            gap: clamp(
                20px,
                8vw,
                40px
            );
        }

        .radio-volume {
            left: 15px;
            bottom: 91px;
        }

        .radio-queue {
            right: 15px;
            bottom: 91px;
        }

        .radio-progress-wrapper {
            bottom: 35px;

            width: 65vw;

            min-width: 170px;
        }

        .queue-panel {
            bottom: 145px;

            max-height: 50vh;
        }
    }


    /* ==========================================================
       SMALL MOBILE
    ========================================================== */

    @media (max-width: 400px) {

        .radio-brand-text {
            font-size: 18px;
        }

        .radio-brand-logo {
            font-size: 25px;
        }

        .radio-header {
            margin-top: 75px;
        }

        .radio-cover-wrapper {
            width: min(
                55vw,
                30vh,
                210px
            );

            margin-top: 25px;
        }

        .radio-song-info {
            margin-top: 8px;
        }

        .radio-controls {
            bottom: 82px;
        }

        .radio-control-group {
            gap: 15px;
        }

        .radio-volume {
            bottom: 82px;
        }

        .radio-queue {
            bottom: 82px;
        }

        .radio-progress-wrapper {
            bottom: 28px;

            width: 70vw;
        }
    }


    /* ==========================================================
       LANDSCAPE PHONE
    ========================================================== */

    @media (
        max-height: 600px
    ) and (
        orientation: landscape
    ) {

        .radio-brand {
            top: 15px;
            left: 20px;
        }

        .radio-header {
            margin-top: 55px;
        }

        .radio-cover-wrapper {
            width: min(
                42vh,
                35vw,
                220px
            );

            margin-top: 15px;
        }

        .radio-song-info {
            margin-top: 5px;
        }

        .radio-controls {
            bottom: 35px;
        }

        .radio-volume {
            bottom: 35px;
        }

        .radio-queue {
            bottom: 35px;
        }

        .radio-progress-wrapper {
            bottom: 10px;
        }

        .queue-panel {
            bottom: 75px;
        }
    }


    /* ==========================================================
       VERY SHORT SCREEN
    ========================================================== */

    @media (max-height: 550px) {

        .radio-cover-wrapper {
            width: min(
                35vh,
                220px
            );

            margin-top: 15px;
        }

        .radio-song-info {
            display: none;
        }

        .radio-controls {
            bottom: 45px;
        }

        .radio-volume {
            bottom: 45px;
        }

        .radio-queue {
            bottom: 45px;
        }

        .radio-progress-wrapper {
            bottom: 15px;
        }
    }

</style>
<link rel="stylesheet" href="{{ asset('css/wavo-shell.css') }}">
</head>


<body class="radio-body">

<div
    class="radio-page"
    data-idle-artist="{{ count($songsData) ? 'Wavo Radio' : 'No music available' }}"
>

    <div class="radio-background"></div>

    <div class="radio-overlay"></div>


    {{-- ==========================================================
         BRAND
    ========================================================== --}}

    <a
        href="{{ route('home') }}"
        class="radio-brand"
    >

        <span class="radio-brand-logo">
            〽
        </span>

        <span class="radio-brand-text">
            Music
        </span>

    </a>


    {{-- ==========================================================
         CONTENT
    ========================================================== --}}

    <main class="radio-content">


        {{-- ======================================================
             STATION HEADER
        ======================================================= --}}

        <div class="radio-header">

            <button
                type="button"
                class="radio-header-button"
                id="previousButton"
                title="Previous"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >

                    <path d="M11 18V6L2.5 12 11 18Z"></path>

                    <path d="M21 18V6L12.5 12 21 18Z"></path>

                </svg>

            </button>


            <div class="radio-station">
                Radio 0001
            </div>


            <button
                type="button"
                class="radio-header-button"
                id="nextButton"
                title="Next"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >

                    <path d="M13 6V18L21.5 12 13 6Z"></path>

                    <path d="M3 6V18L11.5 12 3 6Z"></path>

                </svg>

            </button>

        </div>


        {{-- ======================================================
             COVER
        ======================================================= --}}

        <div class="radio-cover-wrapper">

            <div class="radio-cover-glow"></div>


            <div
                class="radio-cover"
                id="radioCover"
            >

                <div class="radio-cover-placeholder">
                    〽
                </div>

            </div>

        </div>


        {{-- ======================================================
             SONG INFO
        ======================================================= --}}

        <div class="radio-song-info">

            <div
                class="radio-song-title"
                id="radioSongTitle"
            >
                Radio 0001
            </div>

            <div
                class="radio-song-artist"
                id="radioSongArtist"
            >
                Wavo Radio
            </div>

        </div>


        {{-- ======================================================
             VOLUME
        ======================================================= --}}

        <div class="radio-volume">

            <button
                type="button"
                class="radio-volume-button"
                id="volumeButton"
                title="Volume"
            >

                <svg
                    id="volumeIcon"
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

                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>

                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>

                </svg>

            </button>

        </div>


        {{-- ======================================================
             QUEUE
        ======================================================= --}}

        <div class="radio-queue">

            <button
                type="button"
                class="radio-queue-button"
                id="queueButton"
                title="Queue"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >

                    <line x1="3" y1="6" x2="21" y2="6"></line>

                    <line x1="3" y1="12" x2="21" y2="12"></line>

                    <line x1="3" y1="18" x2="15" y2="18"></line>

                </svg>

            </button>

        </div>


        {{-- ======================================================
             CONTROLS
        ======================================================= --}}

        <div class="radio-controls">

            <div class="radio-control-group">

                {{-- SHUFFLE --}}

                <button
                    type="button"
                    class="radio-control-button"
                    id="shuffleButton"
                    title="Shuffle"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
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


                {{-- PLAY --}}

                <button
                    type="button"
                    class="radio-play"
                    id="playButton"
                    title="Play / Pause"
                >

                    <svg
                        id="radioPlayIcon"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >

                        <polygon
                            points="8,5 19,12 8,19"
                        ></polygon>

                    </svg>

                </button>


                {{-- REPEAT --}}

                <button
                    type="button"
                    class="radio-control-button"
                    id="repeatButton"
                    title="Repeat"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >

                        <polyline
                            points="17 1 21 5 17 9"
                        ></polyline>

                        <path
                            d="M3 11V9a4 4 0 0 1 4-4h14"
                        ></path>

                        <polyline
                            points="7 23 3 19 7 15"
                        ></polyline>

                        <path
                            d="M21 13v2a4 4 0 0 1-4 4H3"
                        ></path>

                    </svg>

                </button>

            </div>

        </div>


        {{-- ======================================================
             PROGRESS
        ======================================================= --}}

        <div class="radio-progress-wrapper">

            <span
                class="radio-time"
                id="radioCurrentTime"
            >
                0:00
            </span>


            <div
                class="radio-progress"
                id="radioProgressBar"
            >

                <div
                    class="radio-progress-fill"
                    id="radioProgressFill"
                ></div>

            </div>


            <span
                class="radio-time"
                id="radioTotalTime"
            >
                0:00
            </span>

        </div>

    </main>


    {{-- ==========================================================
         QUEUE PANEL
    ========================================================== --}}

    <div
        class="queue-panel"
        id="queuePanel"
    >

        <div class="queue-title">
            Radio Queue
        </div>

        <select id="radioGenre" class="radio-genre-select" aria-label="Radio genre">
            <option value="">Random Radio</option>
            @foreach(collect($songsData)->pluck('genre')->filter()->unique()->sort() as $genre)
                <option value="{{ $genre }}">{{ $genre }} Radio</option>
            @endforeach
        </select>

        <div id="queueList">

            @foreach($songsData as $song)

                @if(!empty($song['audio']))

                    <button
                        type="button"
                        class="queue-item"
                        data-song-id="{{ $song['id'] }}"
                        data-genre="{{ $song['genre'] ?? '' }}"
                    >

                        <div class="queue-item-cover">

                            @if(!empty($song['cover']))
                                <img src="{{ $song['cover'] }}" alt="">
                            @else
                                〽
                            @endif

                        </div>

                        <div class="queue-item-info">

                            <div class="queue-item-title">
                                {{ $song['title'] ?: 'Unknown' }}
                            </div>

                            <div class="queue-item-artist">
                                {{ $song['artist'] ?: 'Unknown' }}
                            </div>

                        </div>

                    </button>

                @endif

            @endforeach

        </div>

    </div>


    {{-- ==========================================================
         SONG SOURCE (dibaca oleh global player, tidak tampil)
    ========================================================== --}}

    <div
        id="radioSongSource"
        style="display:none"
        aria-hidden="true"
    >

        @foreach($songsData as $song)

            @if(!empty($song['audio']))

                <div
                    class="song-row"
                    data-song-id="{{ $song['id'] }}"
                    data-audio="{{ $song['audio'] }}"
                    data-title="{{ $song['title'] }}"
                    data-artist="{{ $song['artist'] }}"
                    data-cover="{{ $song['cover'] ?? '' }}"
                    data-genre="{{ $song['genre'] ?? '' }}"
                ></div>

            @endif

        @endforeach

    </div>

</div>


{{-- GLOBAL MUSIC PLAYER (satu-satunya <audio>) --}}
@include('partials.music-player')


<script>
/*
|--------------------------------------------------------------------------
| RADIO PAGE — HANYA PENGENDALI GLOBAL PLAYER
|--------------------------------------------------------------------------
|
| Halaman ini TIDAK punya <audio>, queue, atau state playback sendiri.
| Semua dikerjakan oleh window.WavoMusicPlayer + <audio id="globalAudio">
| (partials/music-player.blade.php):
|
|   - Play / Next / Previous / seek  -> API WavoMusicPlayer
|   - Shuffle / Repeat / Mute        -> klik tombol global (#shuffleBtn,
|                                       #repeatBtn, #muteBtn), supaya
|                                       state-nya SATU dan sama di semua
|                                       halaman
|   - Daftar lagu                    -> baris tersembunyi
|                                       #radioSongSource .song-row[data-audio]
|                                       yang otomatis dikenali player global
|                                       sebagai queue (sama seperti halaman lain)
|
| Tampilan Radio (judul, cover, progress, ikon play, toggle) selalu
| digambar ULANG dari state global player. Tidak ada state playback yang
| disimpan di sini.
|
| Turbo:
|   - Script ini dieksekusi ulang di setiap kunjungan ke /radio. Listener
|     di document hanya dipasang SEKALI (window.__wavoRadio.installed);
|     eksekusi berikutnya cuma memanggil render().
|   - Listener tidak menyimpan referensi elemen halaman (selalu resolve
|     saat dipakai) dan tidak melakukan apa pun bila bukan di halaman Radio.
|   - Tidak ada new Audio(), load(), src=, currentTime= atau play()/pause()
|     langsung ke audio dari sini.
*/
(function () {

    'use strict';

    const RADIO = window.__wavoRadio = window.__wavoRadio || {};

    /*
     * Eksekusi ulang oleh Turbo: listener sudah terpasang. Cukup gambar
     * ulang dari state global (render() aman dipanggil kapan saja dan
     * menunggu <audio> asli bila Turbo belum mengembalikannya).
     */
    if (RADIO.installed) {
        RADIO.render();
        return;
    }

    RADIO.installed = true;


    /* ==========================================================
       HELPER
    ========================================================== */

    const DEFAULT_COLORS = [
        [197, 164, 92],
        [140, 112, 64],
        [95, 77, 46]
    ];

    const PLAY_ICON =
        '<polygon points="8,5 19,12 8,19"></polygon>';

    const PAUSE_ICON =
        '<rect x="6" y="5" width="4" height="14"></rect>' +
        '<rect x="14" y="5" width="4" height="14"></rect>';

    const VOLUME_ON =
        '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" fill="currentColor"></polygon>' +
        '<path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>' +
        '<path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>';

    const VOLUME_OFF =
        '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" fill="currentColor"></polygon>' +
        '<line x1="23" y1="9" x2="17" y2="15"></line>' +
        '<line x1="17" y1="9" x2="23" y2="15"></line>';

    function $(id) {
        return document.getElementById(id);
    }

    function player() {
        return window.WavoMusicPlayer || null;
    }

    /*
     * Saat Turbo mengganti <body>, id "globalAudio" sempat dimiliki
     * <meta> placeholder milik Turbo. Hanya terima <audio> yang asli.
     */
    function globalAudio() {

        const element = $('globalAudio');

        return element && element.tagName === 'AUDIO'
            ? element
            : null;
    }

    function hasSong(audio) {
        return !!(audio && audio.getAttribute('src'));
    }

    function onRadioPage() {
        return !!document.querySelector('.radio-page');
    }

    function formatTime(seconds) {

        if (!seconds || !Number.isFinite(seconds)) {
            return '0:00';
        }

        const minutes = Math.floor(seconds / 60);
        const rest = Math.floor(seconds % 60);

        return minutes + ':' + (rest < 10 ? '0' : '') + rest;
    }

    function songRows() {

        return Array.from(
            document.querySelectorAll(
                '#radioSongSource .song-row[data-audio]'
            )
        );
    }


    /* ==========================================================
       AMBIENT COLOR (dari cover)
    ========================================================== */

    let ambientFrame = null;
    let coverRequest = 0;

    function cancelAmbient() {

        if (ambientFrame) {
            cancelAnimationFrame(ambientFrame);
            ambientFrame = null;
        }
    }

    function getAverageColor(colors) {

        let r = 0;
        let g = 0;
        let b = 0;

        colors.forEach(function (color) {
            r += color[0];
            g += color[1];
            b += color[2];
        });

        return [
            Math.round(r / colors.length),
            Math.round(g / colors.length),
            Math.round(b / colors.length)
        ];
    }

    function brightenColor(color, multiplier) {

        return color.map(function (value) {
            return Math.min(255, Math.round(value * multiplier));
        });
    }

    function interpolateColor(from, to, progress) {

        return [
            Math.round(from[0] + (to[0] - from[0]) * progress),
            Math.round(from[1] + (to[1] - from[1]) * progress),
            Math.round(from[2] + (to[2] - from[2]) * progress)
        ];
    }

    function getCurrentRGB(element, property, fallback) {

        const value =
            getComputedStyle(element)
                .getPropertyValue(property)
                .trim();

        const match =
            value.match(/rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/);

        if (!match) {
            return fallback;
        }

        return [
            Number(match[1]),
            Number(match[2]),
            Number(match[3])
        ];
    }

    function setAmbientColors(colors) {

        const page = document.querySelector('.radio-page');

        if (!page) {
            return;
        }

        const from = [
            getCurrentRGB(page, '--ambient-1', DEFAULT_COLORS[0]),
            getCurrentRGB(page, '--ambient-2', DEFAULT_COLORS[1]),
            getCurrentRGB(page, '--ambient-3', DEFAULT_COLORS[2])
        ];

        const startTime = performance.now();
        const duration = 900;

        cancelAmbient();

        function animate(now) {

            const progress = Math.min((now - startTime) / duration, 1);
            const eased = progress * (2 - progress);

            for (let i = 0; i < 3; i++) {

                page.style.setProperty(
                    '--ambient-' + (i + 1),
                    'rgb(' +
                        interpolateColor(from[i], colors[i], eased).join(',') +
                    ')'
                );
            }

            ambientFrame =
                progress < 1
                    ? requestAnimationFrame(animate)
                    : null;
        }

        ambientFrame = requestAnimationFrame(animate);
    }

    function extractCoverColors(imageUrl) {

        const request = ++coverRequest;

        const img = new Image();

        img.crossOrigin = 'anonymous';

        img.onload = function () {

            if (request !== coverRequest) {
                return;
            }

            try {

                const canvas = document.createElement('canvas');

                const ctx = canvas.getContext('2d', {
                    willReadFrequently: true
                });

                canvas.width = 32;
                canvas.height = 32;

                ctx.drawImage(img, 0, 0, 32, 32);

                const data = ctx.getImageData(0, 0, 32, 32).data;

                const colors = [];

                for (let i = 0; i < data.length; i += 16) {

                    const r = data[i];
                    const g = data[i + 1];
                    const b = data[i + 2];
                    const a = data[i + 3];

                    if (a < 180) {
                        continue;
                    }

                    if ((r + g + b) / 3 < 20) {
                        continue;
                    }

                    colors.push([r, g, b]);
                }

                if (!colors.length) {
                    setAmbientColors(DEFAULT_COLORS);
                    return;
                }

                const average = getAverageColor(colors);

                setAmbientColors([
                    brightenColor(average, 1.25),
                    brightenColor(average, .85),
                    brightenColor(average, .55)
                ]);

            } catch (error) {

                console.warn('Tidak bisa membaca warna cover:', error);
            }
        };

        img.onerror = function () {

            if (request === coverRequest) {
                setAmbientColors(DEFAULT_COLORS);
            }
        };

        img.src = imageUrl;
    }


    /* ==========================================================
       RENDER (hanya membaca state global player)
    ========================================================== */

    function renderSongInfo(page, loaded, s) {

        const title = $('radioSongTitle');
        const artist = $('radioSongArtist');
        const cover = $('radioCover');

        if (!title || !artist || !cover) {
            return;
        }

        const coverUrl = loaded ? (s.cover || '') : '';

        title.textContent =
            loaded ? (s.title || 'Unknown') : 'Radio 0001';

        artist.textContent =
            loaded
                ? (s.artist || 'Unknown')
                : (page.dataset.idleArtist || 'Wavo Radio');

        cover.textContent = '';

        if (coverUrl) {

            const img = document.createElement('img');

            img.src = coverUrl;
            img.alt = '';

            cover.appendChild(img);

            extractCoverColors(coverUrl);

        } else {

            const placeholder = document.createElement('div');

            placeholder.className = 'radio-cover-placeholder';
            placeholder.textContent = '〽';

            cover.appendChild(placeholder);

            coverRequest++;

            setAmbientColors(DEFAULT_COLORS);
        }
    }

    function renderPlayIcon(playing) {

        const icon = $('radioPlayIcon');

        if (!icon) {
            return;
        }

        const mode = playing ? 'pause' : 'play';

        if (icon.dataset.mode === mode) {
            return;
        }

        icon.dataset.mode = mode;
        icon.innerHTML = playing ? PAUSE_ICON : PLAY_ICON;
    }

    function renderProgress(loaded, s) {

        const fill = $('radioProgressFill');
        const now = $('radioCurrentTime');
        const total = $('radioTotalTime');

        if (!fill || !now || !total) {
            return;
        }

        const ok =
            loaded &&
            Number.isFinite(s.duration) &&
            s.duration > 0;

        fill.style.width =
            ok
                ? ((s.currentTime / s.duration) * 100) + '%'
                : '0%';

        now.textContent = loaded ? formatTime(s.currentTime) : '0:00';

        total.textContent = ok ? formatTime(s.duration) : '0:00';
    }

    function renderToggles(s) {

        const shuffle = $('shuffleButton');
        const repeat = $('repeatButton');
        const volume = $('volumeIcon');

        if (shuffle) {
            shuffle.classList.toggle('active', !!s.shuffle);
        }

        if (repeat) {
            repeat.classList.toggle('active', !!s.repeat);
        }

        if (volume) {

            const mode = s.muted ? 'off' : 'on';

            if (volume.dataset.mode !== mode) {
                volume.dataset.mode = mode;
                volume.innerHTML = s.muted ? VOLUME_OFF : VOLUME_ON;
            }
        }
    }

    function renderQueueActive(songId) {

        document
            .querySelectorAll('#queueList .queue-item')
            .forEach(function (item) {

                item.classList.toggle(
                    'active',
                    !!songId &&
                    String(item.dataset.songId) === String(songId)
                );
            });
    }

    function render() {

        const page = document.querySelector('.radio-page');
        const P = player();
        const audio = globalAudio();

        if (!page || !P || !audio) {
            return;
        }

        const s = P.getState();
        const loaded = hasSong(audio);
        const playing = loaded && !audio.paused && !audio.ended;

        /*
         * Judul + cover hanya digambar ulang saat lagu berubah (atau saat
         * DOM Radio baru dibuat oleh Turbo), bukan di setiap timeupdate.
         */
        const key =
            loaded
                ? 'song:' + (s.songId || '') + '|' + (s.title || '')
                : 'idle';

        if (page.dataset.renderedSong !== key) {

            page.dataset.renderedSong = key;

            renderSongInfo(page, loaded, s);
        }

        page.classList.toggle('is-playing', playing);

        renderPlayIcon(playing);
        renderProgress(loaded, s);
        renderToggles(s);
        renderQueueActive(loaded ? s.songId : '');
    }

    RADIO.render = render;


    /* ==========================================================
       KONTROL -> GLOBAL PLAYER
    ========================================================== */

    function startRandomSong() {

        const P = player();
        const rows = songRows();
        const genre = $('radioGenre') ? $('radioGenre').value : '';
        const filteredRows = rows.filter(function (row) {
            return !genre || row.dataset.genre === genre;
        });

        if (!P || filteredRows.length === 0) {
            return;
        }

        P.startRadio(filteredRows, genre);
        renderQueue(filteredRows);
    }

    function renderQueue(rows) {
        const list = $('queueList');

        if (!list) {
            return;
        }

        list.querySelectorAll('.queue-item').forEach(function (item) {
            item.hidden = !rows.some(function (row) {
                return String(row.dataset.songId) === String(item.dataset.songId);
            });
        });
    }

    function playPause() {

        const P = player();

        if (!P) {
            return;
        }

        if (!hasSong(globalAudio())) {
            startRandomSong();
        } else {
            P.toggle();
        }

        render();
    }

    function next() {

        const P = player();

        if (!P) {
            return;
        }

        if (!hasSong(globalAudio())) {
            startRandomSong();
        } else {
            P.next();
        }

        render();
    }

    function previous() {

        const P = player();

        if (!P) {
            return;
        }

        if (!hasSong(globalAudio())) {
            startRandomSong();
        } else {
            P.previous();
        }

        render();
    }

    /*
     * Shuffle / repeat / mute adalah state milik controller global.
     * Satu-satunya jalur mengubahnya adalah tombol global, jadi Radio
     * "menekan" tombol itu. Klik programatik tetap bekerja walau bar
     * global disembunyikan di halaman Radio.
     */
    function pressGlobalButton(id) {

        const button = $(id);

        if (button) {
            button.click();
        }

        render();
    }

    function seekFromEvent(bar, event) {

        const P = player();

        if (!P) {
            return;
        }

        const s = P.getState();

        if (!Number.isFinite(s.duration) || s.duration <= 0) {
            return;
        }

        const rect = bar.getBoundingClientRect();

        if (rect.width <= 0) {
            return;
        }

        const percent =
            Math.max(
                0,
                Math.min(1, (event.clientX - rect.left) / rect.width)
            );

        P.seek(percent * s.duration);

        render();
    }

    function playQueueItem(item) {

        const P = player();

        if (!P) {
            return;
        }

        P.playRadioById(item.dataset.songId);

        render();
    }


    /* ==========================================================
       EVENT (event delegation, SEKALI di document)
    ========================================================== */

    document.addEventListener('click', function (event) {

        const target = event.target;

        if (!target || !target.closest || !onRadioPage()) {
            return;
        }

        if (target.closest('#playButton')) {
            playPause();
            return;
        }

        if (target.closest('#nextButton')) {
            next();
            return;
        }

        if (target.closest('#previousButton')) {
            previous();
            return;
        }

        if (target.closest('#shuffleButton')) {
            pressGlobalButton('shuffleBtn');
            return;
        }

        if (target.closest('#repeatButton')) {
            pressGlobalButton('repeatBtn');
            return;
        }

        if (target.closest('#volumeButton')) {
            pressGlobalButton('muteBtn');
            return;
        }

        const bar = target.closest('#radioProgressBar');

        if (bar) {
            seekFromEvent(bar, event);
            return;
        }

        const panel = $('queuePanel');

        if (target.closest('#queueButton')) {

            if (panel) {
                panel.classList.toggle('show');
            }

            return;
        }

        const item = target.closest('#queueList .queue-item');

        if (item) {
            playQueueItem(item);
            return;
        }

        if (panel && !target.closest('#queuePanel')) {
            panel.classList.remove('show');
        }
    });

    document.addEventListener('change', function (event) {
        if (event.target && event.target.id === 'radioGenre' && onRadioPage()) {
            startRandomSong();
        }
    });

    /*
     * Event media tidak bubble, jadi pakai capture di document. Satu
     * listener ini cukup untuk <audio id="globalAudio"> walau elemen
     * itu dipindahkan Turbo (tidak ada listener yang menempel ke elemen).
     */
    [
        'play',
        'pause',
        'timeupdate',
        'loadedmetadata',
        'durationchange',
        'emptied',
        'ended',
        'volumechange'
    ].forEach(function (name) {

        document.addEventListener(name, function (event) {

            if (event.target && event.target.id === 'globalAudio') {
                render();
            }

        }, true);
    });

    /*
     * Turbo: gambar ulang setelah <body> baru + elemen permanen terpasang,
     * dan hentikan animasi ambient saat meninggalkan halaman.
     */
    function initializeRadioQueue() {
        const P = player();
        const select = $('radioGenre');
        const rows = songRows();

        if (!P || !select || rows.length === 0) {
            render();
            return;
        }

        if (P.isRadioActive()) {
            select.value = P.getRadioGenre() || '';
            renderQueue(rows.filter(function (row) {
                return !select.value || row.dataset.genre === select.value;
            }));
        } else {
            const filteredRows = rows.filter(function (row) {
                return !select.value || row.dataset.genre === select.value;
            });

            P.setRadioQueue(filteredRows, select.value);
            renderQueue(filteredRows);
        }

        render();
    }

    document.addEventListener('turbo:render', initializeRadioQueue);
    document.addEventListener('turbo:load', initializeRadioQueue);

    document.addEventListener('turbo:before-render', cancelAmbient);


    /* ==========================================================
       INITIAL
    ========================================================== */

    initializeRadioQueue();

})();
</script>


</body>
</html>
