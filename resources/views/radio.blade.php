<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Radio - Wavo Music</title>

    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html,
    body {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    body {
        background: #202020;
        color: #fff;
        font-family: Arial, Helvetica, sans-serif;
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
</head>


<body>

<div class="radio-page">

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
                        id="playIcon"
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
                id="currentTime"
            >
                0:00
            </span>


            <div
                class="radio-progress"
                id="progressBar"
            >

                <div
                    class="radio-progress-fill"
                    id="progressFill"
                ></div>

            </div>


            <span
                class="radio-time"
                id="totalTime"
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

        <div id="queueList"></div>

    </div>


    {{-- ==========================================================
         AUDIO
    ========================================================== --}}

    <audio
        id="radioAudio"
        preload="metadata"
    ></audio>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* ==========================================================
       SONG DATA
    ========================================================== */

    const songs = @json($songsData);


    const audio =
        document.getElementById('radioAudio');

    const cover =
        document.getElementById('radioCover');

    const title =
        document.getElementById('radioSongTitle');

    const artist =
        document.getElementById('radioSongArtist');

    const playButton =
        document.getElementById('playButton');

    const playIcon =
        document.getElementById('playIcon');

    const previousButton =
        document.getElementById('previousButton');

    const nextButton =
        document.getElementById('nextButton');

    const shuffleButton =
        document.getElementById('shuffleButton');

    const repeatButton =
        document.getElementById('repeatButton');

    const volumeButton =
        document.getElementById('volumeButton');

    const volumeIcon =
        document.getElementById('volumeIcon');

    const queueButton =
        document.getElementById('queueButton');

    const queuePanel =
        document.getElementById('queuePanel');

    const queueList =
        document.getElementById('queueList');

    const progressBar =
        document.getElementById('progressBar');

    const progressFill =
        document.getElementById('progressFill');

    const currentTime =
        document.getElementById('currentTime');

    const totalTime =
        document.getElementById('totalTime');


    let currentIndex = -1;

    let shuffle = false;

    let repeat = false;

    let muted = false;


    /* ==========================================================
       FORMAT TIME
    ========================================================== */

    function formatTime(seconds) {

        if (
            !seconds ||
            isNaN(seconds)
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
            (
                secondsPart < 10
                    ? '0'
                    : ''
            ) +
            secondsPart
        );

    }


    /* ==========================================================
       COVER
    ========================================================== */

    function updateCover(song) {
    if (song && song.cover) {
        cover.innerHTML = `<img src="${song.cover}" alt="">`;

        extractCoverColors(song.cover);
    } else {
        cover.innerHTML = `<div class="radio-cover-placeholder">〽</div>`;

        setAmbientColors([
            [197, 164, 92],
            [140, 112, 64],
            [95, 77, 46]
        ]);
    }

    function extractCoverColors(imageUrl) {
    const img = new Image();

    img.crossOrigin = "anonymous";

    img.onload = function () {
        try {
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d", {
                willReadFrequently: true
            });

            canvas.width = 32;
            canvas.height = 32;

            ctx.drawImage(img, 0, 0, 32, 32);

            const imageData = ctx.getImageData(
                0,
                0,
                32,
                32
            ).data;

            const colors = [];

            for (let i = 0; i < imageData.length; i += 16) {
                const r = imageData[i];
                const g = imageData[i + 1];
                const b = imageData[i + 2];
                const a = imageData[i + 3];

                if (a < 180) continue;

                const brightness =
                    (r + g + b) / 3;

                if (brightness < 20) continue;

                colors.push([r, g, b]);
            }

            if (!colors.length) {
                setAmbientColors([
                    [197, 164, 92],
                    [140, 112, 64],
                    [95, 77, 46]
                ]);

                return;
            }

            const average = getAverageColor(colors);

            const color1 = brightenColor(
                average,
                1.25
            );

            const color2 = brightenColor(
                average,
                .85
            );

            const color3 = brightenColor(
                average,
                .55
            );

            setAmbientColors([
                color1,
                color2,
                color3
            ]);

        } catch (error) {
            console.warn(
                "Tidak bisa membaca warna cover:",
                error
            );
        }
    };

    img.onerror = function () {
        setAmbientColors([
            [197, 164, 92],
            [140, 112, 64],
            [95, 77, 46]
        ]);
    };

    img.src = imageUrl;
}


function getAverageColor(colors) {
    let r = 0;
    let g = 0;
    let b = 0;

    colors.forEach(color => {
        r += color[0];
        g += color[1];
        b += color[2];
    });

    const count = colors.length;

    return [
        Math.round(r / count),
        Math.round(g / count),
        Math.round(b / count)
    ];
}


function brightenColor(color, multiplier) {
    return color.map(value => {
        return Math.min(
            255,
            Math.round(value * multiplier)
        );
    });
}


let ambientAnimationFrame = null;

function setAmbientColors(colors) {
    const page = document.querySelector(".radio-page");

    if (!page) return;

    const target = {
        c1: colors[0],
        c2: colors[1],
        c3: colors[2]
    };

    const current = {
        c1: getCurrentRGB(
            page,
            "--ambient-1",
            [197, 164, 92]
        ),

        c2: getCurrentRGB(
            page,
            "--ambient-2",
            [140, 112, 64]
        ),

        c3: getCurrentRGB(
            page,
            "--ambient-3",
            [95, 77, 46]
        )
    };

    const startTime = performance.now();
    const duration = 900;

    if (ambientAnimationFrame) {
        cancelAnimationFrame(
            ambientAnimationFrame
        );
    }

    function animate(now) {
        const progress = Math.min(
            (now - startTime) / duration,
            1
        );

        const eased =
            progress * (2 - progress);

        const c1 = interpolateColor(
            current.c1,
            target.c1,
            eased
        );

        const c2 = interpolateColor(
            current.c2,
            target.c2,
            eased
        );

        const c3 = interpolateColor(
            current.c3,
            target.c3,
            eased
        );

        page.style.setProperty(
            "--ambient-1",
            `rgb(${c1.join(",")})`
        );

        page.style.setProperty(
            "--ambient-2",
            `rgb(${c2.join(",")})`
        );

        page.style.setProperty(
            "--ambient-3",
            `rgb(${c3.join(",")})`
        );

        if (progress < 1) {
            ambientAnimationFrame =
                requestAnimationFrame(animate);
        }
    }

    ambientAnimationFrame =
        requestAnimationFrame(animate);
}


function interpolateColor(from, to, progress) {
    return [
        Math.round(
            from[0] +
            (to[0] - from[0]) * progress
        ),

        Math.round(
            from[1] +
            (to[1] - from[1]) * progress
        ),

        Math.round(
            from[2] +
            (to[2] - from[2]) * progress
        )
    ];
}


function getCurrentRGB(
    element,
    property,
    fallback
) {
    const value =
        getComputedStyle(element)
            .getPropertyValue(property)
            .trim();

    const match =
        value.match(
            /rgb\\(\\s*(\\d+)\\s*,\\s*(\\d+)\\s*,\\s*(\\d+)\\s*\\)/
        );

    if (!match) {
        return fallback;
    }

    return [
        Number(match[1]),
        Number(match[2]),
        Number(match[3])
    ];
}
}


    /* ==========================================================
       PLAY
    ========================================================== */

    function playSong() {

        if (!songs.length) {

            return;

        }


        if (currentIndex === -1) {

            loadSong(0);

        }


        audio.play().catch(() => {});

    }


    /* ==========================================================
       PLAY / PAUSE ICON
    ========================================================== */

    function updatePlayIcon() {

        if (audio.paused) {

            playIcon.innerHTML = `
                <polygon
                    points="8,5 19,12 8,19"
                ></polygon>
            `;

        } else {

            playIcon.innerHTML = `
                <rect
                    x="6"
                    y="5"
                    width="4"
                    height="14"
                ></rect>

                <rect
                    x="14"
                    y="5"
                    width="4"
                    height="14"
                ></rect>
            `;

        }

    }


    /* ==========================================================
       NEXT
    ========================================================== */

    function nextSong() {

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

                }
                while (
                    nextIndex === currentIndex
                );

            }

        } else {

            nextIndex =
                currentIndex + 1;


            if (
                nextIndex >= songs.length
            ) {

                nextIndex = 0;

            }

        }


        loadSong(nextIndex, true);

        audio.play().catch(() => {});

    }


    /* ==========================================================
       PREVIOUS
    ========================================================== */

    function previousSong() {

        if (!songs.length) {
            return;
        }


        let previousIndex =
            currentIndex - 1;


        if (
            previousIndex < 0
        ) {

            previousIndex =
                songs.length - 1;

        }


        loadSong(previousIndex, true);

        audio.play().catch(() => {});

    }


    /* ==========================================================
       PLAY BUTTON
    ========================================================== */

    playButton.addEventListener(
        'click',
        function () {

            if (!songs.length) {
                return;
            }


            if (
                currentIndex === -1
            ) {

                loadSong(0);

            }


            if (audio.paused) {

                audio
                    .play()
                    .catch(() => {});

            } else {

                audio.pause();

            }

        }
    );


    /* ==========================================================
       NEXT / PREVIOUS
    ========================================================== */

    nextButton.addEventListener(
        'click',
        nextSong
    );


    previousButton.addEventListener(
        'click',
        previousSong
    );


    /* ==========================================================
       SHUFFLE
    ========================================================== */

    shuffleButton.addEventListener(
        'click',
        function () {

            shuffle =
                !shuffle;

            shuffleButton.classList.toggle(
                'active',
                shuffle
            );

        }
    );


    /* ==========================================================
       REPEAT
    ========================================================== */

    repeatButton.addEventListener(
        'click',
        function () {

            repeat =
                !repeat;

            repeatButton.classList.toggle(
                'active',
                repeat
            );

        }
    );


    /* ==========================================================
       VOLUME
    ========================================================== */

    volumeButton.addEventListener(
        'click',
        function () {

            muted =
                !muted;

            audio.muted =
                muted;


            if (muted) {

                volumeIcon.innerHTML = `
                    <polygon
                        points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"
                        fill="currentColor"
                    ></polygon>

                    <line
                        x1="23"
                        y1="9"
                        x2="17"
                        y2="15"
                    ></line>

                    <line
                        x1="17"
                        y1="9"
                        x2="23"
                        y2="15"
                    ></line>
                `;

            } else {

                volumeIcon.innerHTML = `
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
                `;

            }

        }
    );


    /* ==========================================================
       AUDIO EVENTS
    ========================================================== */

    audio.addEventListener("play", () => {
    document
        .querySelector(".radio-page")
        ?.classList.add("is-playing");
});

audio.addEventListener("pause", () => {
    document
        .querySelector(".radio-page")
        ?.classList.remove("is-playing");
});


    audio.addEventListener(
        'loadedmetadata',
        function () {

            totalTime.textContent =
                formatTime(
                    audio.duration
                );

        }
    );


    audio.addEventListener(
        'timeupdate',
        function () {

            const percent =
                audio.duration
                    ? (
                        audio.currentTime /
                        audio.duration
                    ) * 100
                    : 0;


            progressFill.style.width =
                percent + '%';


            currentTime.textContent =
                formatTime(
                    audio.currentTime
                );

        }
    );


    audio.addEventListener("ended", () => {
    document
        .querySelector(".radio-page")
        ?.classList.remove("is-playing");
});


    /* ==========================================================
       PROGRESS CLICK
    ========================================================== */

    progressBar.addEventListener(
        'click',
        function (e) {

            if (
                !audio.duration
            ) {
                return;
            }


            const rect =
                progressBar.getBoundingClientRect();


            const percentage =
                (
                    e.clientX -
                    rect.left
                ) / rect.width;


            audio.currentTime =
                percentage *
                audio.duration;

        }
    );


    /* ==========================================================
       QUEUE
    ========================================================== */

    function updateQueue() {

        queueList.innerHTML = '';


        songs.forEach(
            function (song, index) {

                const button =
                    document.createElement(
                        'button'
                    );


                button.type =
                    'button';


                button.className =
                    'queue-item';


                if (
                    index === currentIndex
                ) {

                    button.classList.add(
                        'active'
                    );

                }


                button.innerHTML = `

                    <div class="queue-item-cover">

                        ${
                            song.cover
                                ? `
                                    <img
                                        src="${song.cover}"
                                        alt=""
                                    >
                                `
                                : '〽'
                        }

                    </div>

                    <div class="queue-item-info">

                        <div class="queue-item-title">

                            ${escapeHtml(
                                song.title ||
                                'Unknown'
                            )}

                        </div>

                        <div class="queue-item-artist">

                            ${escapeHtml(
                                song.artist ||
                                'Unknown'
                            )}

                        </div>

                    </div>

                `;


                button.addEventListener(
                    'click',
                    function () {

                        loadSong(
                            index
                        );

                        audio
                            .play()
                            .catch(() => {});

                    }
                );


                queueList.appendChild(
                    button
                );

            }
        );

    }


    queueButton.addEventListener(
        'click',
        function () {

            queuePanel.classList.toggle(
                'show'
            );

        }
    );


    document.addEventListener(
        'click',
        function (e) {

            if (
                !e.target.closest(
                    '#queuePanel'
                ) &&
                !e.target.closest(
                    '#queueButton'
                )
            ) {

                queuePanel.classList.remove(
                    'show'
                );

            }

        }
    );


    /* ==========================================================
       ESCAPE HTML
    ========================================================== */

    function escapeHtml(text) {

        const div =
            document.createElement('div');

        div.textContent =
            text ?? '';

        return div.innerHTML;

    }


    /* ==========================================================
       INITIAL
    ========================================================== */

    if (songs.length > 0) {

        loadSong(0);

    } else {

        title.textContent =
            'Radio 0001';

        artist.textContent =
            'No music available';

    }

});

</script>

</body>
</html>