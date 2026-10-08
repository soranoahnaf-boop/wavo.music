{{--
|--------------------------------------------------------------------------
| WAVO FULL-SCREEN PLAYER
|--------------------------------------------------------------------------
| Included once by partials/music-player.blade.php.
|
| - Opens when the cover / title in the mini player is clicked.
| - Two screens (the two dots at the top): "Playing Music" and
|   "Playing Lyrics", matching the Figma design.
| - It is only a second VIEW of the one global <audio>. It never creates
|   audio, never changes src, and talks to the player through
|   window.WavoMusicPlayer, so playback continues across Turbo navigation
|   and when the overlay is opened / closed.
|
| Lyrics format (songs.lyrics):
|   Synced (LRC):  [01:12.50] Line of the song
|   Plain text:    one line per row (shown without highlighting)
--}}

<link rel="preconnect" href="https://fonts.bunny.net">
<link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:500,600,700&display=swap">

<style>
    /* =========================================================
       FULL-SCREEN PLAYER
    ========================================================= */

    html.fp-lock,
    html.fp-lock body {
        overflow: hidden !important;
        scrollbar-gutter: auto !important;
    }

    #fullPlayer {
        --fp-gold: #c5a45c;
        --fp-bg: #232323;
        --fp-bar: min(61vw, 900px);
        --fp-cover: min(34vh, 62vw, 460px);
        --fp-ease: cubic-bezier(.2, .8, .2, 1);

        position: fixed;
        inset: 0;
        z-index: 3000;

        display: flex;
        flex-direction: column;

        background: var(--fp-bg);
        color: #fff;
        overflow: hidden;

        font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI",
            Roboto, "Helvetica Neue", Arial, sans-serif;
        -webkit-font-smoothing: antialiased;

        transform: translateY(100%);
        opacity: 0;
        visibility: hidden;

        transition:
            transform .5s var(--fp-ease),
            opacity .35s ease,
            visibility 0s linear .5s;
    }

    #fullPlayer.is-open {
        transform: none;
        opacity: 1;
        visibility: visible;

        transition:
            transform .5s var(--fp-ease),
            opacity .35s ease,
            visibility 0s;
    }

    #fullPlayer *,
    #fullPlayer *::before,
    #fullPlayer *::after {
        box-sizing: border-box;
    }

    #fullPlayer button {
        appearance: none;
        -webkit-appearance: none;
        border: 0;
        background: none;
        padding: 0;
        margin: 0;
        font: inherit;
        color: inherit;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }

    #fullPlayer button:focus-visible,
    #fullPlayer [role="slider"]:focus-visible {
        outline: 2px solid var(--fp-gold);
        outline-offset: 4px;
    }

    #fullPlayer svg {
        display: block;
    }

    #fullPlayer [hidden] {
        display: none !important;
    }

    /* ---------------------------------------------------------
       BACKGROUND — blurred gold light on charcoal
    --------------------------------------------------------- */

    .fp-bg {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .fp-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(70px);
        will-change: transform;
    }

    .fp-blob-a {
        left: -14vw;
        top: -4vh;
        width: 78vw;
        height: 24vw;
        background: radial-gradient(
            ellipse at 50% 50%,
            rgba(190, 154, 82, .85) 0%,
            rgba(150, 118, 58, .55) 45%,
            rgba(120, 92, 40, 0) 75%
        );
        transform: rotate(-36deg);
        animation: fp-drift-a 26s ease-in-out infinite alternate;
    }

    .fp-blob-b {
        right: -22vw;
        bottom: -14vh;
        width: 96vw;
        height: 26vw;
        background: radial-gradient(
            ellipse at 50% 50%,
            rgba(230, 184, 98, 1) 0%,
            rgba(200, 156, 78, .75) 45%,
            rgba(150, 112, 50, 0) 75%
        );
        transform: rotate(-30deg);
        animation: fp-drift-b 32s ease-in-out infinite alternate;
    }

    .fp-glint {
        position: absolute;
        width: 9vw;
        height: 9vw;
        border-radius: 50%;
        filter: blur(34px);
        background: rgba(255, 244, 220, .55);
        pointer-events: none;
    }

    .fp-glint-a { left: 24vw; top: 12vh; }
    .fp-glint-b { right: 12vw; bottom: 26vh; }

    @keyframes fp-drift-a {
        from { transform: rotate(-36deg) translate3d(0, 0, 0); }
        to   { transform: rotate(-30deg) translate3d(4vw, 3vh, 0); }
    }

    @keyframes fp-drift-b {
        from { transform: rotate(-30deg) translate3d(0, 0, 0); }
        to   { transform: rotate(-36deg) translate3d(-5vw, -3vh, 0); }
    }

    /* ---------------------------------------------------------
       HEADER — logo, page dots, close
    --------------------------------------------------------- */

    .fp-header {
        position: relative;
        z-index: 3;

        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;

        padding:
            calc(clamp(18px, 3.4vh, 40px) + env(safe-area-inset-top, 0px))
            clamp(20px, 4vw, 56px)
            0;
    }

    .fp-brand {
        display: flex;
        align-items: center;
        gap: clamp(12px, 1.6vw, 22px);
        min-width: 0;
    }

    .fp-brand svg {
        width: clamp(40px, 4vw, 62px);
        height: auto;
        flex-shrink: 0;
    }

    .fp-brand span {
        font-size: clamp(16px, 1.4vw, 24px);
        font-weight: 500;
        letter-spacing: .01em;
    }

    .fp-dots {
        display: flex;
        align-items: center;
        gap: clamp(14px, 2vw, 28px);
    }

    #fullPlayer .fp-dot {
        width: clamp(16px, 1.6vw, 22px);
        height: clamp(16px, 1.6vw, 22px);
        border-radius: 50%;

        background: radial-gradient(
            circle at 35% 30%,
            #9a9a95 0%,
            #55554f 55%,
            #3d3d39 100%
        );

        box-shadow: 0 2px 6px rgba(0, 0, 0, .35);

        transition: transform .25s ease, background .25s ease;
    }

    #fullPlayer .fp-dot:hover {
        transform: scale(1.12);
    }

    #fullPlayer .fp-dot.is-active {
        background: radial-gradient(
            circle at 35% 30%,
            #ffffff 0%,
            #e4e4e2 50%,
            #a8a8a4 100%
        );
    }

    .fp-close {
        justify-self: end;

        width: clamp(38px, 3vw, 46px);
        height: clamp(38px, 3vw, 46px);
        border-radius: 50%;

        display: flex !important;
        align-items: center;
        justify-content: center;

        background: rgba(255, 255, 255, .14) !important;

        transition: background .2s ease, transform .2s ease;
    }

    .fp-close:hover {
        background: rgba(255, 255, 255, .26) !important;
    }

    .fp-close svg {
        width: 55%;
        height: 55%;
    }

    /* ---------------------------------------------------------
       PANES — Playing Music / Playing Lyrics
    --------------------------------------------------------- */

    .fp-views {
        position: relative;
        z-index: 2;
        flex: 1;
        min-height: 0;
    }

    .fp-pane {
        position: absolute;
        inset: 0;

        opacity: 0;
        visibility: hidden;
        pointer-events: none;

        transition:
            opacity .45s ease,
            transform .55s var(--fp-ease),
            visibility 0s linear .55s;
    }

    .fp-pane-player {
        transform: translateX(-6vw);
    }

    .fp-pane-lyrics {
        transform: translateX(6vw);
    }

    #fullPlayer[data-view="player"] .fp-pane-player,
    #fullPlayer[data-view="lyrics"] .fp-pane-lyrics {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: none;

        transition:
            opacity .45s ease,
            transform .55s var(--fp-ease),
            visibility 0s;
    }

    /* ---------------------------------------------------------
       PLAYING MUSIC
    --------------------------------------------------------- */

    .fp-pane-player {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        padding:
            clamp(8px, 2vh, 24px)
            clamp(16px, 4vw, 56px)
            calc(clamp(16px, 4vh, 56px) + env(safe-area-inset-bottom, 0px));
    }

    .fp-cover {
        width: var(--fp-cover);
        height: var(--fp-cover);
        flex-shrink: 0;

        border-radius: 11%;
        overflow: hidden;

        background: #1a1a1a;
        box-shadow: 0 24px 60px rgba(0, 0, 0, .45);

        display: flex;
        align-items: center;
        justify-content: center;

        color: var(--fp-gold);
        font-size: calc(var(--fp-cover) * .35);

        transition: transform .5s var(--fp-ease);
    }

    #fullPlayer.is-paused .fp-cover {
        transform: scale(.94);
    }

    .fp-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .fp-meta {
        width: var(--fp-bar);
        max-width: 100%;
        text-align: center;
        margin-top: clamp(14px, 3.2vh, 40px);
    }

    .fp-title {
        font-size: clamp(22px, 2.4vw, 44px);
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -.01em;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fp-artist {
        margin-top: clamp(2px, .5vh, 8px);

        font-size: clamp(13px, 1.5vw, 28px);
        font-weight: 600;
        text-transform: uppercase;
        color: #a1a1a1;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fp-actions {
        position: relative;
        width: var(--fp-bar);
        max-width: 100%;

        display: flex;
        justify-content: flex-end;
        gap: clamp(8px, 1vw, 14px);

        margin-top: clamp(4px, 1.4vh, 18px);
    }

    .fp-round {
        width: clamp(34px, 2.5vw, 44px);
        height: clamp(34px, 2.5vw, 44px);
        border-radius: 50%;

        display: flex !important;
        align-items: center;
        justify-content: center;

        background: rgba(255, 255, 255, .26) !important;
        color: #fff;

        transition: background .2s ease, transform .2s ease, color .2s ease;
    }

    .fp-round:hover {
        background: rgba(255, 255, 255, .4) !important;
    }

    .fp-round:active {
        transform: scale(.92);
    }

    .fp-round svg {
        width: 52%;
        height: 52%;
    }

    #fullPlayer .fp-fav .fp-fav-fill {
        display: none;
    }

    #fullPlayer .fp-fav.is-on {
        color: var(--fp-gold);
    }

    #fullPlayer .fp-fav.is-on .fp-fav-fill {
        display: block;
    }

    #fullPlayer .fp-fav.is-on .fp-fav-outline {
        display: none;
    }

    /* ---------- progress ---------- */

    .fp-seek {
        width: var(--fp-bar);
        max-width: 100%;
        margin-top: clamp(8px, 1.6vh, 20px);
    }

    .fp-track {
        position: relative;

        height: clamp(24px, 2vw, 34px);
        display: flex;
        align-items: center;

        cursor: pointer;
        touch-action: none;
        outline-offset: 2px;
    }

    .fp-track-rail {
        position: relative;
        width: 100%;
        height: clamp(8px, .75vw, 13px);
        border-radius: 999px;
        background: rgba(255, 255, 255, .38);
        overflow: hidden;
    }

    .fp-track-fill {
        position: absolute;
        inset: 0 auto 0 0;
        width: 100%;
        background: #e6e6e6;
        border-radius: 999px;
        transform-origin: left center;
        transform: scaleX(0);
    }

    .fp-thumb {
        position: absolute;
        top: 50%;
        left: 0;

        width: clamp(20px, 1.5vw, 28px);
        height: clamp(20px, 1.5vw, 28px);
        margin: calc(clamp(20px, 1.5vw, 28px) / -2) 0 0
                calc(clamp(20px, 1.5vw, 28px) / -2);

        border-radius: 50%;
        background: #dcdcdc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .45);

        pointer-events: none;
        transition: transform .15s ease;
    }

    .fp-track.is-dragging .fp-thumb {
        transform: scale(1.18);
    }

    .fp-times {
        display: flex;
        justify-content: space-between;

        margin-top: 2px;

        font-size: clamp(12px, 1.1vw, 20px);
        font-weight: 500;
        color: rgba(255, 255, 255, .78);
        font-variant-numeric: tabular-nums;
    }

    /* ---------- transport ---------- */

    .fp-controls {
        width: var(--fp-bar);
        max-width: 100%;

        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;

        margin-top: clamp(6px, 2vh, 28px);
    }

    .fp-controls-left  { justify-self: start; }
    .fp-controls-right { justify-self: end; }

    .fp-cluster {
        display: flex;
        align-items: center;
        gap: clamp(16px, 3.4vw, 64px);
    }

    .fp-ctl {
        display: flex !important;
        align-items: center;
        justify-content: center;

        width: clamp(34px, 2.6vw, 48px);
        height: clamp(34px, 2.6vw, 48px);

        color: rgba(255, 255, 255, .92);

        transition: transform .15s ease, color .2s ease, opacity .2s ease;
    }

    .fp-ctl:hover {
        transform: scale(1.1);
    }

    .fp-ctl:active {
        transform: scale(.92);
    }

    .fp-ctl svg {
        width: 100%;
        height: 100%;
    }

    .fp-ctl-small {
        width: clamp(26px, 2vw, 36px);
        height: clamp(26px, 2vw, 36px);
        opacity: .7;
    }

    .fp-ctl-small.is-on {
        color: var(--fp-gold);
        opacity: 1;
    }

    .fp-ctl-play {
        width: clamp(48px, 3.8vw, 72px);
        height: clamp(48px, 3.8vw, 72px);
        color: #fff;
    }

    .fp-ctl-vol .fp-x {
        display: none;
    }

    .fp-ctl-vol.is-on {
        color: #ff6b61;
        opacity: 1;
    }

    .fp-ctl-vol.is-on .fp-waves {
        display: none;
    }

    .fp-ctl-vol.is-on .fp-x {
        display: block;
    }

    /* ---------------------------------------------------------
       PLAYING LYRICS
    --------------------------------------------------------- */

    .fp-lyrics {
        position: absolute;
        inset: 0;

        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: none;
        overscroll-behavior: contain;

        padding: 33vh clamp(20px, 6vw, 120px) 45vh;

        -webkit-mask-image: linear-gradient(
            to bottom,
            transparent 0,
            #000 14%,
            #000 82%,
            transparent 100%
        );
        mask-image: linear-gradient(
            to bottom,
            transparent 0,
            #000 14%,
            #000 82%,
            transparent 100%
        );
    }

    .fp-lyrics::-webkit-scrollbar {
        display: none;
    }

    /* plain (unsynced) lyrics start near the top */
    .fp-lyrics.is-plain {
        padding-top: 14vh;
    }

    .fp-lines {
        max-width: 1280px;
        margin: 0 auto;
    }

    .fp-line {
        margin: 0 0 clamp(18px, 3.6vh, 44px);

        text-align: center;
        font-size: clamp(22px, 2.75vw, 56px);
        font-weight: 700;
        line-height: 1.22;
        letter-spacing: -.01em;
        color: #fff;

        opacity: .3;
        transform: scale(.94);
        transform-origin: center;

        transition:
            opacity .45s ease,
            transform .45s var(--fp-ease);

        overflow-wrap: anywhere;
    }

    .fp-line.is-section {
        font-size: clamp(26px, 3.9vw, 80px);
        margin-bottom: clamp(34px, 7vh, 96px);
    }

    .fp-line.is-active {
        transform: scale(1);
    }

    .fp-line.is-gap {
        letter-spacing: .3em;
    }

    .fp-line.is-spacer {
        height: clamp(10px, 2vh, 24px);
        margin: 0;
        visibility: hidden;
    }

    .fp-lines.is-synced .fp-line {
        cursor: pointer;
    }

    /* plain (unsynced) lyrics: everything readable */

    .fp-lines.is-plain .fp-line {
        opacity: .92;
        transform: none;
        font-size: clamp(18px, 2vw, 34px);
        margin-bottom: clamp(10px, 2vh, 22px);
    }

    .fp-lines.is-plain .fp-line.is-section {
        font-size: clamp(20px, 2.4vw, 40px);
        margin-top: clamp(24px, 5vh, 56px);
        color: var(--fp-gold);
    }

    .fp-lyrics-note {
        position: absolute;
        left: 0;
        right: 0;
        bottom: calc(clamp(14px, 3vh, 30px) + env(safe-area-inset-bottom, 0px));

        text-align: center;
        font-size: 12px;
        letter-spacing: .04em;
        color: rgba(255, 255, 255, .5);

        pointer-events: none;
    }

    .fp-empty {
        position: absolute;
        inset: 0;

        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;

        padding: 24px;
        text-align: center;
    }

    .fp-empty.is-visible {
        display: flex;
    }

    .fp-empty strong {
        font-size: clamp(20px, 2.2vw, 36px);
        font-weight: 700;
    }

    .fp-empty span {
        font-size: clamp(13px, 1.1vw, 18px);
        color: rgba(255, 255, 255, .6);
    }

    /* ---------------------------------------------------------
       MENU (add to playlist) + QUEUE + TOAST
    --------------------------------------------------------- */

    .fp-menu {
        position: absolute;
        right: 0;
        bottom: calc(100% + 10px);
        z-index: 6;

        width: min(280px, 86vw);
        max-height: 40vh;
        overflow-y: auto;

        padding: 8px;
        border-radius: 16px;

        background: rgba(38, 38, 38, .96);
        border: 1px solid rgba(255, 255, 255, .12);
        box-shadow: 0 18px 40px rgba(0, 0, 0, .5);
        backdrop-filter: blur(18px);

        display: none;
    }

    .fp-menu.is-open {
        display: block;
    }

    .fp-menu-title {
        padding: 8px 10px 6px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .5);
    }

    .fp-menu-item {
        display: block !important;
        width: 100%;
        padding: 10px !important;
        border-radius: 10px;

        text-align: left;
        font-size: 14px !important;
        color: #f2f2f2;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fp-menu-item:hover {
        background: rgba(255, 255, 255, .1) !important;
    }

    .fp-menu-hint {
        padding: 10px;
        font-size: 13px;
        color: rgba(255, 255, 255, .55);
    }

    .fp-queue {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 8;

        width: min(420px, 100%);

        display: flex;
        flex-direction: column;

        padding-top: env(safe-area-inset-top, 0px);

        background: rgba(30, 30, 30, .94);
        border-left: 1px solid rgba(255, 255, 255, .1);
        backdrop-filter: blur(22px);

        transform: translateX(100%);
        visibility: hidden;

        transition:
            transform .4s var(--fp-ease),
            visibility 0s linear .4s;
    }

    .fp-queue.is-open {
        transform: none;
        visibility: visible;

        transition:
            transform .4s var(--fp-ease),
            visibility 0s;
    }

    .fp-queue-head {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 22px 22px 12px;

        font-size: 20px;
        font-weight: 700;
    }

    .fp-queue-list {
        flex: 1;
        overflow-y: auto;
        padding: 6px 12px calc(20px + env(safe-area-inset-bottom, 0px));
    }

    .fp-queue-item {
        display: flex !important;
        align-items: center;
        gap: 12px;

        width: 100%;
        padding: 8px !important;
        border-radius: 12px;

        text-align: left;
    }

    .fp-queue-item:hover {
        background: rgba(255, 255, 255, .08) !important;
    }

    .fp-queue-item.is-current {
        background: rgba(197, 164, 92, .18) !important;
    }

    .fp-queue-art {
        width: 46px;
        height: 46px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;

        background: #1a1a1a;
        color: var(--fp-gold);

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fp-queue-art img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .fp-queue-text {
        min-width: 0;
    }

    .fp-queue-text b,
    .fp-queue-text small {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fp-queue-text b {
        font-size: 14px;
        font-weight: 600;
    }

    .fp-queue-text small {
        margin-top: 2px;
        font-size: 12px;
        color: rgba(255, 255, 255, .55);
    }

    .fp-toast {
        position: absolute;
        left: 50%;
        bottom: calc(24px + env(safe-area-inset-bottom, 0px));
        z-index: 9;

        padding: 10px 18px;
        border-radius: 999px;

        background: rgba(20, 20, 20, .92);
        border: 1px solid rgba(255, 255, 255, .12);
        font-size: 13px;

        opacity: 0;
        transform: translate(-50%, 12px);
        pointer-events: none;

        transition: opacity .25s ease, transform .25s ease;
    }

    .fp-toast.is-visible {
        opacity: 1;
        transform: translate(-50%, 0);
    }

    /* ---------------------------------------------------------
       RESPONSIVE
    --------------------------------------------------------- */

    @media (max-width: 700px) {

        #fullPlayer {
            --fp-bar: 88vw;
            --fp-cover: min(40vh, 74vw);
        }

        .fp-brand span {
            display: none;
        }

        .fp-controls-left .fp-ctl-small,
        .fp-controls-right .fp-ctl-small {
            width: 30px;
            height: 30px;
        }

        .fp-cluster {
            gap: 18px;
        }

        .fp-lyrics {
            padding-top: 26vh;
        }
    }

    @media (max-height: 560px) {

        #fullPlayer {
            --fp-cover: min(30vh, 40vw);
        }
    }

    @media (prefers-reduced-motion: reduce) {

        #fullPlayer,
        .fp-pane,
        .fp-line,
        .fp-cover,
        .fp-queue {
            transition-duration: .01s !important;
        }

        .fp-blob {
            animation: none !important;
        }
    }
</style>


{{-- =============================================================
     FULL-SCREEN PLAYER UI
================================================================= --}}

<div
    id="fullPlayer"
    data-turbo-permanent
    data-view="player"
    role="dialog"
    aria-modal="true"
    aria-label="Now playing"
    aria-hidden="true"
    inert
>

    {{-- BACKGROUND --}}

    <div class="fp-bg" aria-hidden="true">
        <div class="fp-blob fp-blob-a"></div>
        <div class="fp-blob fp-blob-b"></div>
        <div class="fp-glint fp-glint-a"></div>
        <div class="fp-glint fp-glint-b"></div>
    </div>


    {{-- HEADER --}}

    <div class="fp-header">

        <div class="fp-brand">

            <svg viewBox="0 0 64 40" fill="none" stroke="#c5a45c" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M26 12l-2-8 5 4 3-6 3 6 5-4-2 8z"></path>
                <path d="M2 30c4-6 8-6 11 0s7 6 10-2 5-14 9-14 7 8 10 14 7 6 10 0 5-4 8 2"></path>
                <path d="M2 36c4-4 8-4 11 0s7 4 10 0 7-4 9 0 7 4 10 0 7-4 11 0"></path>
            </svg>

            <span>Music</span>

        </div>

        <div class="fp-dots" role="tablist" aria-label="Player screens">

            <button
                type="button"
                class="fp-dot is-active"
                role="tab"
                aria-selected="true"
                aria-label="Player"
                data-fp-view="player"
            ></button>

            <button
                type="button"
                class="fp-dot"
                role="tab"
                aria-selected="false"
                aria-label="Lyrics"
                data-fp-view="lyrics"
            ></button>

        </div>

        <button
            type="button"
            class="fp-close"
            id="fpClose"
            title="Close"
            aria-label="Close full-screen player"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="5 9 12 16 19 9"></polyline>
            </svg>
        </button>

    </div>


    {{-- SCREENS --}}

    <div class="fp-views" id="fpViews">

        {{-- ========== PLAYING MUSIC ========== --}}

        <section class="fp-pane fp-pane-player" aria-label="Playing music">

            <div class="fp-cover" id="fpCover">〽</div>

            <div class="fp-meta">
                <div class="fp-title" id="fpTitle">Not Playing</div>
                <div class="fp-artist" id="fpArtist">Select a song</div>
            </div>

            <div class="fp-actions" id="fpActions">

                <button type="button" class="fp-round fp-fav" id="fpFav" title="Favorite" aria-label="Favorite" aria-pressed="false" hidden>
                    <svg class="fp-fav-outline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2.8 14.9 8.7 21.4 9.6 16.7 14.2 17.8 20.7 12 17.6 6.2 20.7 7.3 14.2 2.6 9.6 9.1 8.7"></polygon>
                    </svg>
                    <svg class="fp-fav-fill" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2.8 14.9 8.7 21.4 9.6 16.7 14.2 17.8 20.7 12 17.6 6.2 20.7 7.3 14.2 2.6 9.6 9.1 8.7"></polygon>
                    </svg>
                </button>

                <button type="button" class="fp-round" id="fpMore" title="More" aria-label="More options" aria-haspopup="true" aria-expanded="false" hidden>
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="5" cy="12" r="2"></circle>
                        <circle cx="12" cy="12" r="2"></circle>
                        <circle cx="19" cy="12" r="2"></circle>
                    </svg>
                </button>

                <div class="fp-menu" id="fpMenu" role="menu"></div>

            </div>

            <div class="fp-seek">

                <div
                    class="fp-track"
                    id="fpTrack"
                    role="slider"
                    tabindex="0"
                    aria-label="Seek"
                    aria-valuemin="0"
                    aria-valuemax="0"
                    aria-valuenow="0"
                >
                    <div class="fp-track-rail">
                        <div class="fp-track-fill" id="fpFill"></div>
                    </div>
                    <div class="fp-thumb" id="fpThumb"></div>
                </div>

                <div class="fp-times">
                    <span id="fpElapsed">0:00</span>
                    <span id="fpRemaining">-0:00</span>
                </div>

            </div>

            <div class="fp-controls">

                <div class="fp-controls-left">
                    <button type="button" class="fp-ctl fp-ctl-small fp-ctl-vol" id="fpMute" title="Mute" aria-label="Mute">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="11 5 6 9 2.5 9 2.5 15 6 15 11 19 11 5" fill="currentColor"></polygon>
                            <g class="fp-waves">
                                <path d="M15.5 8.5a5 5 0 0 1 0 7"></path>
                                <path d="M19 5.5a9.5 9.5 0 0 1 0 13"></path>
                            </g>
                            <g class="fp-x">
                                <line x1="16" y1="9" x2="22" y2="15"></line>
                                <line x1="22" y1="9" x2="16" y2="15"></line>
                            </g>
                        </svg>
                    </button>
                </div>

                <div class="fp-cluster">

                    <button type="button" class="fp-ctl fp-ctl-small" id="fpShuffle" title="Shuffle" aria-label="Shuffle" aria-pressed="false">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 3 21 3 21 8"></polyline>
                            <line x1="4" y1="20" x2="21" y2="3"></line>
                            <polyline points="21 16 21 21 16 21"></polyline>
                            <line x1="15" y1="15" x2="21" y2="21"></line>
                            <line x1="4" y1="4" x2="9" y2="9"></line>
                        </svg>
                    </button>

                    <button type="button" class="fp-ctl" id="fpPrev" title="Previous" aria-label="Previous">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round">
                            <path d="M11 6.5v11L3 12zM21 6.5v11L13 12z"></path>
                        </svg>
                    </button>

                    <button type="button" class="fp-ctl fp-ctl-play" id="fpPlay" title="Play" aria-label="Play">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round">
                            <path id="fpPlayPath" d="M7 4.5v15L20 12z"></path>
                        </svg>
                    </button>

                    <button type="button" class="fp-ctl" id="fpNext" title="Next" aria-label="Next">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round">
                            <path d="M13 6.5v11L21 12zM3 6.5v11L11 12z"></path>
                        </svg>
                    </button>

                    <button type="button" class="fp-ctl fp-ctl-small" id="fpRepeat" title="Repeat" aria-label="Repeat" aria-pressed="false">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="17 1 21 5 17 9"></polyline>
                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                            <polyline points="7 23 3 19 7 15"></polyline>
                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                        </svg>
                    </button>

                </div>

                <div class="fp-controls-right">
                    <button type="button" class="fp-ctl fp-ctl-small" id="fpQueueBtn" title="Up next" aria-label="Up next">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <rect x="2" y="4.5" width="3" height="3" rx="1"></rect>
                            <rect x="7" y="4.5" width="15" height="3" rx="1"></rect>
                            <rect x="2" y="10.5" width="3" height="3" rx="1"></rect>
                            <rect x="7" y="10.5" width="15" height="3" rx="1"></rect>
                            <rect x="2" y="16.5" width="3" height="3" rx="1"></rect>
                            <rect x="7" y="16.5" width="15" height="3" rx="1"></rect>
                        </svg>
                    </button>
                </div>

            </div>

        </section>


        {{-- ========== PLAYING LYRICS ========== --}}

        <section class="fp-pane fp-pane-lyrics" aria-label="Lyrics">

            <div class="fp-lyrics" id="fpLyrics">
                <div class="fp-lines" id="fpLines"></div>
            </div>

            <div class="fp-empty" id="fpEmpty">
                <strong id="fpEmptyTitle">No lyrics yet</strong>
                <span id="fpEmptyText">Lyrics for this song haven't been added.</span>
            </div>

            <div class="fp-lyrics-note" id="fpLyricsNote" hidden></div>

        </section>

    </div>


    {{-- UP NEXT --}}

    <aside class="fp-queue" id="fpQueue" aria-label="Up next" aria-hidden="true" inert>

        <div class="fp-queue-head">
            <span>Up Next</span>

            <button type="button" class="fp-round" id="fpQueueClose" title="Close" aria-label="Close queue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="fp-queue-list" id="fpQueueList"></div>

    </aside>

    <div class="fp-toast" id="fpToast" role="status" aria-live="polite"></div>

</div>


<script>
/*
|--------------------------------------------------------------------------
| WAVO FULL-SCREEN PLAYER CONTROLLER
|--------------------------------------------------------------------------
|
| - Runs once. Turbo re-executes this inline script on every navigation, so
|   a guard flag makes later executions a no-op.
| - Reads everything from window.WavoMusicPlayer.getState() while the screen
|   is open (requestAnimationFrame loop). Nothing runs while it is closed.
| - Never touches <audio> directly.
|
*/
(function () {

    'use strict';

    if (window.__WAVO_FULL_PLAYER_INITIALIZED) {
        return;
    }

    window.__WAVO_FULL_PLAYER_INITIALIZED = true;

    const LYRICS_URL = @json(url('/songs'));
    const PLAYLISTS_URL = @json(url('/playlists/mine'));

    const PLAY_PATH  = 'M7 4.5v15L20 12z';
    const PAUSE_PATH = 'M7 4.5h3.6v15H7zM13.4 4.5H17v15h-3.6z';

    /* Opacity by distance from the active line (Figma: fades downward) */
    const FUTURE_OPACITY = [1, .78, .62, .42, .3, .22, .16];
    const PAST_OPACITY   = [1, .38, .26, .18, .12];

    const reduceMotion = window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;


    /*
    |--------------------------------------------------------------------------
    | LYRICS PARSER (LRC + plain text)
    |--------------------------------------------------------------------------
    */

    /*LRC-START*/
    function isSectionLabel(text) {
        return /^\[[^\]]+\]$/.test(text);
    }

    function parseLyrics(raw) {

        if (raw === null || raw === undefined || !String(raw).trim()) {
            return { synced: false, lines: [] };
        }

        const rows = String(raw).replace(/\r\n?/g, '\n').split('\n');

        const TIME = /^\s*\[(\d{1,3}):(\d{1,2})(?:[.:](\d{1,3}))?\]/;
        const META = /^\s*\[(ti|ar|al|au|by|length|offset|re|ve|tool|id):([^\]]*)\]\s*$/i;
        const WORD = /<\d{1,3}:\d{1,2}(?:[.:]\d{1,3})?>/g;

        let offsetMs = 0;

        const timed = [];
        const plain = [];

        rows.forEach(function (row) {

            const meta = META.exec(row);

            if (meta) {

                if (meta[1].toLowerCase() === 'offset') {
                    offsetMs = parseInt(meta[2], 10) || 0;
                }

                return;
            }

            const times = [];
            let rest = row;
            let match;

            while ((match = TIME.exec(rest))) {

                const fraction = match[3]
                    ? parseInt(match[3], 10) / Math.pow(10, match[3].length)
                    : 0;

                times.push(
                    parseInt(match[1], 10) * 60 +
                    parseInt(match[2], 10) +
                    fraction
                );

                rest = rest.slice(match[0].length);
            }

            const text = rest.replace(WORD, '').trim();

            if (times.length) {

                times.forEach(function (time) {
                    timed.push({ time: time, text: text });
                });

            } else {

                plain.push(text);
            }
        });

        if (timed.length) {

            timed.forEach(function (line) {
                line.time = Math.max(0, line.time - offsetMs / 1000);
            });

            timed.sort(function (a, b) {
                return a.time - b.time;
            });

            return {
                synced: true,
                lines: timed.map(function (line) {
                    return {
                        time: line.time,
                        text: line.text,
                        section: isSectionLabel(line.text),
                        gap: line.text === '',
                        spacer: false
                    };
                })
            };
        }

        /* Plain text: trim blank edges, collapse repeated blank rows. */

        const lines = [];

        plain.forEach(function (text) {

            if (text === '') {

                if (lines.length && !lines[lines.length - 1].spacer) {
                    lines.push({
                        time: null, text: '', section: false,
                        gap: false, spacer: true
                    });
                }

                return;
            }

            lines.push({
                time: null,
                text: text,
                section: isSectionLabel(text),
                gap: false,
                spacer: false
            });
        });

        while (lines.length && lines[lines.length - 1].spacer) {
            lines.pop();
        }

        return { synced: false, lines: lines };
    }

    /* Last line whose time is <= t (binary search); -1 before the first. */
    function findActiveIndex(lines, t) {

        let lo = 0;
        let hi = lines.length - 1;
        let answer = -1;

        while (lo <= hi) {

            const mid = (lo + hi) >> 1;

            if (lines[mid].time <= t) {
                answer = mid;
                lo = mid + 1;
            } else {
                hi = mid - 1;
            }
        }

        return answer;
    }
    /*LRC-END*/


    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    const view = {
        open: false,
        page: 'player',
        songId: '',
        raf: null,
        lastFocus: null,

        dragging: false,
        dragPercent: 0,

        shownSecond: -1,
        shownDuration: -1,
        shownPercent: -1,
        shownPaused: null,

        lyrics: null,          // { synced, lines }
        lyricsFor: '',
        lineEls: [],
        activeIndex: -2,
        followUntil: 0,

        favorites: new Map(),
        playlists: null,

        toastTimer: null
    };

    const lyricsCache = new Map();


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    let cachedRoot = null;
    let cachedRefs = null;

    function getRoot() {

        const element = document.getElementById('fullPlayer');

        /* During a Turbo render the id can briefly belong to a placeholder. */
        return element && element.tagName === 'DIV' ? element : null;
    }

    function refs() {

        const root = getRoot();

        if (!root) {
            return null;
        }

        if (cachedRoot === root && cachedRefs) {
            return cachedRefs;
        }

        const byId = function (id) {
            return root.querySelector('#' + id);
        };

        cachedRoot = root;

        cachedRefs = {
            root: root,
            cover: byId('fpCover'),
            title: byId('fpTitle'),
            artist: byId('fpArtist'),
            fav: byId('fpFav'),
            more: byId('fpMore'),
            menu: byId('fpMenu'),
            actions: byId('fpActions'),
            track: byId('fpTrack'),
            fill: byId('fpFill'),
            thumb: byId('fpThumb'),
            elapsed: byId('fpElapsed'),
            remaining: byId('fpRemaining'),
            play: byId('fpPlay'),
            playPath: byId('fpPlayPath'),
            shuffle: byId('fpShuffle'),
            repeat: byId('fpRepeat'),
            mute: byId('fpMute'),
            lyrics: byId('fpLyrics'),
            lines: byId('fpLines'),
            empty: byId('fpEmpty'),
            emptyTitle: byId('fpEmptyTitle'),
            emptyText: byId('fpEmptyText'),
            note: byId('fpLyricsNote'),
            queue: byId('fpQueue'),
            queueList: byId('fpQueueList'),
            toast: byId('fpToast')
        };

        return cachedRefs;
    }

    function player() {
        return window.WavoMusicPlayer || null;
    }

    function formatTime(seconds) {

        if (!seconds || !Number.isFinite(seconds) || seconds < 0) {
            return '0:00';
        }

        const minutes = Math.floor(seconds / 60);
        const rest = Math.floor(seconds % 60);

        return minutes + ':' + (rest < 10 ? '0' : '') + rest;
    }

    function toast(message) {

        const r = refs();

        if (!r) {
            return;
        }

        r.toast.textContent = message;
        r.toast.classList.add('is-visible');

        clearTimeout(view.toastTimer);

        view.toastTimer = setTimeout(function () {
            r.toast.classList.remove('is-visible');
        }, 2200);
    }

    function postJson(url) {

        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': (window.WAVO && window.WAVO.csrf) || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(function (response) {
            return response.json();
        });
    }

    function isAuthed() {
        return !!(window.WAVO && window.WAVO.auth);
    }


    /*
    |--------------------------------------------------------------------------
    | OPEN / CLOSE
    |--------------------------------------------------------------------------
    */

    function openPlayer() {

        const r = refs();
        const api = player();

        if (!r || !api || view.open) {
            return;
        }

        const state = api.getState();

        /* Nothing loaded yet: nothing to show. */
        if (!state.songId) {
            return;
        }

        view.lastFocus = document.activeElement;
        view.open = true;

        r.root.inert = false;
        r.root.removeAttribute('inert');
        r.root.setAttribute('aria-hidden', 'false');

        document.documentElement.classList.add('fp-lock');

        /* force a fresh paint of everything the first frame */
        view.songId = '';
        view.shownSecond = -1;
        view.shownDuration = -1;
        view.shownPercent = -1;
        view.shownPaused = null;

        render();

        requestAnimationFrame(function () {

            r.root.classList.add('is-open');

            const close = r.root.querySelector('#fpClose');

            if (close) {
                close.focus({ preventScroll: true });
            }
        });

        startLoop();
    }

    function closePlayer() {

        const r = refs();

        if (!r || !view.open) {
            return;
        }

        view.open = false;

        stopLoop();
        closeMenu();
        closeQueue();

        r.root.classList.remove('is-open');
        r.root.setAttribute('aria-hidden', 'true');
        r.root.setAttribute('inert', '');

        document.documentElement.classList.remove('fp-lock');

        const back = view.lastFocus;

        view.lastFocus = null;

        if (back && back.focus && document.contains(back)) {
            back.focus({ preventScroll: true });
        }
    }

    function setPage(page) {

        const r = refs();

        if (!r || (page !== 'player' && page !== 'lyrics')) {
            return;
        }

        view.page = page;

        r.root.dataset.view = page;

        r.root.querySelectorAll('[data-fp-view]').forEach(function (dot) {

            const active = dot.dataset.fpView === page;

            dot.classList.toggle('is-active', active);
            dot.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        closeMenu();

        if (page === 'lyrics') {
            view.activeIndex = -2;
            updateLyricsPosition(true);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER LOOP (only while open)
    |--------------------------------------------------------------------------
    */

    function startLoop() {

        if (view.raf) {
            return;
        }

        view.raf = requestAnimationFrame(tick);
    }

    function stopLoop() {

        if (view.raf) {
            cancelAnimationFrame(view.raf);
        }

        view.raf = null;
    }

    function tick() {

        view.raf = null;

        if (!view.open) {
            return;
        }

        render();

        view.raf = requestAnimationFrame(tick);
    }

    function render() {

        const r = refs();
        const api = player();

        if (!r || !api) {
            return;
        }

        const state = api.getState();

        if (!state.songId) {

            /* Player was stopped while the screen was open. */
            closePlayer();

            return;
        }

        if (String(state.songId) !== view.songId) {
            onSongChanged(state);
        }

        renderPlayState(r, state);
        renderProgress(r, state);
        renderToggles(r, state);
        updateLyricsPosition(false, state);
    }

    function onSongChanged(state) {

        const r = refs();

        view.songId = String(state.songId);

        r.title.textContent = state.title || 'Unknown';
        r.artist.textContent = state.artist || 'Unknown';

        r.cover.textContent = '';

        if (state.cover) {

            const img = document.createElement('img');

            img.src = state.cover;
            img.alt = '';

            r.cover.appendChild(img);

        } else {

            r.cover.textContent = '〽';
        }

        view.shownSecond = -1;
        view.shownDuration = -1;
        view.shownPercent = -1;

        closeMenu();
        renderFavorite();
        renderQueue();

        loadLyrics(view.songId);
    }

    function renderPlayState(r, state) {

        if (view.shownPaused === state.paused) {
            return;
        }

        view.shownPaused = state.paused;

        const label = state.paused ? 'Play' : 'Pause';

        r.playPath.setAttribute('d', state.paused ? PLAY_PATH : PAUSE_PATH);
        r.play.title = label;
        r.play.setAttribute('aria-label', label);

        r.root.classList.toggle('is-paused', state.paused);
    }

    function renderProgress(r, state) {

        const duration = Number.isFinite(state.duration) && state.duration > 0
            ? state.duration
            : 0;

        let time = state.currentTime || 0;
        let percent = duration ? Math.min(1, time / duration) : 0;

        if (view.dragging) {
            percent = view.dragPercent;
            time = percent * duration;
        }

        /* bar + thumb (only when it moved at least a hair) */

        if (Math.abs(percent - view.shownPercent) > 0.0002) {

            view.shownPercent = percent;

            r.fill.style.transform = 'scaleX(' + percent + ')';
            r.thumb.style.left = (percent * 100) + '%';
        }

        /* labels (only when the second changes) */

        const second = Math.floor(time);

        if (second !== view.shownSecond || duration !== view.shownDuration) {

            view.shownSecond = second;
            view.shownDuration = duration;

            r.elapsed.textContent = formatTime(time);

            r.remaining.textContent = duration
                ? '-' + formatTime(Math.max(0, duration - time))
                : '-0:00';

            r.track.setAttribute('aria-valuemax', String(Math.round(duration)));
            r.track.setAttribute('aria-valuenow', String(Math.round(time)));
            r.track.setAttribute('aria-valuetext', formatTime(time));
        }
    }

    function renderToggles(r, state) {

        r.shuffle.classList.toggle('is-on', !!state.shuffle);
        r.shuffle.setAttribute('aria-pressed', state.shuffle ? 'true' : 'false');

        r.repeat.classList.toggle('is-on', !!state.repeat);
        r.repeat.setAttribute('aria-pressed', state.repeat ? 'true' : 'false');

        r.mute.classList.toggle('is-on', !!state.muted);

        r.mute.title = state.muted ? 'Unmute' : 'Mute';
        r.mute.setAttribute('aria-label', state.muted ? 'Unmute' : 'Mute');
    }


    /*
    |--------------------------------------------------------------------------
    | SEEK (pointer drag + keyboard)
    |--------------------------------------------------------------------------
    */

    function percentFromEvent(event) {

        const r = refs();
        const rect = r.track.getBoundingClientRect();

        if (rect.width <= 0) {
            return 0;
        }

        return Math.max(0, Math.min(1, (event.clientX - rect.left) / rect.width));
    }

    function bindSeek() {

        document.addEventListener('pointerdown', function (event) {

            const track = event.target.closest && event.target.closest('#fpTrack');

            if (!track || !view.open) {
                return;
            }

            view.dragging = true;
            view.dragPercent = percentFromEvent(event);

            track.classList.add('is-dragging');

            try {
                track.setPointerCapture(event.pointerId);
            } catch (error) { /* ignore */ }

            event.preventDefault();
        });

        document.addEventListener('pointermove', function (event) {

            if (!view.dragging) {
                return;
            }

            view.dragPercent = percentFromEvent(event);
        });

        function endDrag(event) {

            if (!view.dragging) {
                return;
            }

            view.dragging = false;

            const r = refs();
            const api = player();

            if (r) {
                r.track.classList.remove('is-dragging');
            }

            if (event.type === 'pointercancel' || !api) {
                return;
            }

            const state = api.getState();

            if (Number.isFinite(state.duration) && state.duration > 0) {
                api.seek(view.dragPercent * state.duration);
            }
        }

        document.addEventListener('pointerup', endDrag);
        document.addEventListener('pointercancel', endDrag);
    }


    /*
    |--------------------------------------------------------------------------
    | LYRICS
    |--------------------------------------------------------------------------
    */

    function loadLyrics(id) {

        const r = refs();

        view.lyrics = null;
        view.lyricsFor = id;
        view.lineEls = [];
        view.activeIndex = -2;

        r.lines.textContent = '';
        r.lines.className = 'fp-lines';
        r.lyrics.classList.remove('is-plain');

        showEmpty(r, null);
        showNote(r, '');

        if (lyricsCache.has(id)) {

            applyLyrics(id, lyricsCache.get(id));

            return;
        }

        showEmpty(r, { title: 'Loading lyrics…', text: '' });

        fetch(LYRICS_URL + '/' + encodeURIComponent(id) + '/lyrics', {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) {

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                return response.json();
            })
            .then(function (data) {

                const parsed = parseLyrics(data.lyrics);

                lyricsCache.set(id, parsed);

                applyLyrics(id, parsed);
            })
            .catch(function () {

                if (view.lyricsFor !== id) {
                    return;
                }

                showEmpty(refs(), {
                    title: "Couldn't load lyrics",
                    text: 'Check your connection and reopen the player.'
                });
            });
    }

    function showEmpty(r, content) {

        if (!r) {
            return;
        }

        if (!content) {
            r.empty.classList.remove('is-visible');
            return;
        }

        r.emptyTitle.textContent = content.title;
        r.emptyText.textContent = content.text || '';
        r.empty.classList.add('is-visible');
    }

    function showNote(r, text) {

        r.note.hidden = !text;
        r.note.textContent = text || '';
    }

    function applyLyrics(id, parsed) {

        /* A newer song may have been selected while this was loading. */
        if (view.lyricsFor !== id) {
            return;
        }

        const r = refs();

        view.lyrics = parsed;

        if (!parsed.lines.length) {

            showEmpty(r, {
                title: 'No lyrics yet',
                text: "Lyrics for this song haven't been added."
            });

            return;
        }

        showEmpty(r, null);

        r.lines.className =
            'fp-lines ' + (parsed.synced ? 'is-synced' : 'is-plain');

        r.lyrics.classList.toggle('is-plain', !parsed.synced);

        showNote(r, parsed.synced ? '' : 'Lyrics are not time-synced for this song');

        const fragment = document.createDocumentFragment();

        view.lineEls = parsed.lines.map(function (line, index) {

            const element = document.createElement('p');

            element.className = 'fp-line';
            element.dataset.index = String(index);

            if (line.section) { element.classList.add('is-section'); }
            if (line.gap)     { element.classList.add('is-gap'); }
            if (line.spacer)  { element.classList.add('is-spacer'); }

            element.textContent = line.gap ? '♪ ♪ ♪' : line.text;

            fragment.appendChild(element);

            return element;
        });

        r.lines.appendChild(fragment);

        view.activeIndex = -2;

        updateLyricsPosition(true);
    }

    function lineOpacity(distance) {

        if (distance >= 0) {
            return FUTURE_OPACITY[Math.min(distance, FUTURE_OPACITY.length - 1)];
        }

        return PAST_OPACITY[Math.min(-distance, PAST_OPACITY.length - 1)];
    }

    /*
     * Keeps the highlighted line in sync with audio.currentTime.
     * `immediate` = jump instead of smooth scroll (used when the screen
     * becomes visible or lyrics just loaded).
     */
    function updateLyricsPosition(immediate, knownState) {

        const lyrics = view.lyrics;

        if (!lyrics || !lyrics.synced || !lyrics.lines.length) {
            return;
        }

        const api = player();

        if (!api) {
            return;
        }

        const state = knownState || api.getState();

        /* small look-ahead so the line lights up as it is sung */
        const index = findActiveIndex(lyrics.lines, (state.currentTime || 0) + 0.15);

        if (index === view.activeIndex && !immediate) {
            return;
        }

        const changed = index !== view.activeIndex;

        view.activeIndex = index;

        if (changed || immediate) {

            for (let i = 0; i < view.lineEls.length; i++) {

                const element = view.lineEls[i];
                const distance = i - index;

                /* untouched far-away lines keep their resting style */
                element.classList.toggle('is-active', distance === 0);

                element.style.opacity = String(
                    distance === 0 ? 1 : lineOpacity(distance)
                );
            }
        }

        if (view.page !== 'lyrics') {
            return;
        }

        if (!immediate && Date.now() < view.followUntil) {
            return;
        }

        scrollToLine(Math.max(0, index), immediate || reduceMotion);
    }

    function scrollToLine(index, instant) {

        const r = refs();
        const element = view.lineEls[index];

        if (!r || !element) {
            return;
        }

        const target =
            element.offsetTop +
            element.offsetHeight / 2 -
            r.lyrics.clientHeight * 0.34;

        r.lyrics.scrollTo({
            top: Math.max(0, target),
            behavior: instant ? 'auto' : 'smooth'
        });
    }

    function bindLyricsInteraction() {

        /* user scrolls by hand -> stop auto-follow for a moment */

        const pause = function (event) {

            if (event.target.closest && event.target.closest('#fpLyrics')) {
                view.followUntil = Date.now() + 3500;
            }
        };

        document.addEventListener('wheel', pause, { passive: true });
        document.addEventListener('touchmove', pause, { passive: true });

        /* tap a line -> jump there */

        document.addEventListener('click', function (event) {

            const element = event.target.closest && event.target.closest('.fp-lines.is-synced .fp-line');

            if (!element || !view.lyrics || !view.lyrics.synced) {
                return;
            }

            const line = view.lyrics.lines[Number(element.dataset.index)];
            const api = player();

            if (!line || !api) {
                return;
            }

            api.seek(line.time);

            view.followUntil = 0;

            updateLyricsPosition(true);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | FAVORITE + "MORE" MENU (add to playlist)
    |--------------------------------------------------------------------------
    */

    function pageFavoriteState(id) {

        const button = document.querySelector(
            '.favorite-button[data-song-id="' + id + '"]'
        );

        return button ? button.classList.contains('favorited') : null;
    }

    function renderFavorite() {

        const r = refs();

        if (!r) {
            return;
        }

        const authed = isAuthed();

        r.fav.hidden = !authed;
        r.more.hidden = !authed;

        if (!authed) {
            return;
        }

        /* Prefer what the page already shows, else what we last saw. */

        const fromPage = pageFavoriteState(view.songId);

        if (fromPage !== null) {
            view.favorites.set(view.songId, fromPage);
        }

        const on = !!view.favorites.get(view.songId);

        r.fav.classList.toggle('is-on', on);
        r.fav.setAttribute('aria-pressed', on ? 'true' : 'false');
    }

    function syncPageFavoriteButtons(id, on) {

        document
            .querySelectorAll('.favorite-button[data-song-id="' + id + '"]')
            .forEach(function (button) {
                button.classList.toggle('favorited', on);
            });
    }

    function toggleFavorite() {

        if (!isAuthed() || !view.songId) {
            return;
        }

        const id = view.songId;
        const was = !!view.favorites.get(id);

        view.favorites.set(id, !was);
        syncPageFavoriteButtons(id, !was);
        renderFavorite();

        const base = (window.WAVO.urls && window.WAVO.urls.favorite) || '/home';

        postJson(base + '/' + encodeURIComponent(id) + '/favorite')
            .then(function (data) {

                if (!data || !data.success) {
                    throw new Error('failed');
                }

                view.favorites.set(id, !!data.favorited);
                syncPageFavoriteButtons(id, !!data.favorited);

                if (view.songId === id) {
                    renderFavorite();
                }

                toast(data.favorited ? 'Added to Favorites' : 'Removed from Favorites');
            })
            .catch(function () {

                view.favorites.set(id, was);
                syncPageFavoriteButtons(id, was);

                if (view.songId === id) {
                    renderFavorite();
                }

                toast("Couldn't update favorites");
            });
    }

    function closeMenu() {

        const r = refs();

        if (!r) {
            return;
        }

        r.menu.classList.remove('is-open');
        r.more.setAttribute('aria-expanded', 'false');
    }

    function renderMenu(items, hint) {

        const r = refs();

        r.menu.textContent = '';

        const title = document.createElement('div');

        title.className = 'fp-menu-title';
        title.textContent = 'Add to playlist';

        r.menu.appendChild(title);

        if (hint) {

            const note = document.createElement('div');

            note.className = 'fp-menu-hint';
            note.textContent = hint;

            r.menu.appendChild(note);
        }

        (items || []).forEach(function (playlist) {

            const button = document.createElement('button');

            button.type = 'button';
            button.className = 'fp-menu-item';
            button.setAttribute('role', 'menuitem');
            button.dataset.playlistId = String(playlist.id);
            button.textContent = playlist.name;

            r.menu.appendChild(button);
        });
    }

    function toggleMenu() {

        const r = refs();

        if (!r) {
            return;
        }

        if (r.menu.classList.contains('is-open')) {
            closeMenu();
            return;
        }

        r.menu.classList.add('is-open');
        r.more.setAttribute('aria-expanded', 'true');

        if (view.playlists) {

            renderMenu(
                view.playlists,
                view.playlists.length ? '' : 'You have no playlists yet.'
            );

            return;
        }

        renderMenu([], 'Loading…');

        fetch(PLAYLISTS_URL, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) {

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                return response.json();
            })
            .then(function (list) {

                view.playlists = Array.isArray(list) ? list : [];

                renderMenu(
                    view.playlists,
                    view.playlists.length ? '' : 'You have no playlists yet.'
                );
            })
            .catch(function () {
                renderMenu([], "Couldn't load playlists.");
            });
    }

    function addToPlaylist(playlistId) {

        const id = view.songId;

        if (!id) {
            return;
        }

        const base = (window.WAVO.urls && window.WAVO.urls.playlistAddSong) || '/playlist';

        closeMenu();

        postJson(
            base + '/' + encodeURIComponent(playlistId) +
            '/song/' + encodeURIComponent(id)
        )
            .then(function (data) {
                toast(data && data.success ? 'Added to playlist' : "Couldn't add to playlist");
            })
            .catch(function () {
                toast("Couldn't add to playlist");
            });
    }


    /*
    |--------------------------------------------------------------------------
    | UP NEXT (queue)
    |--------------------------------------------------------------------------
    */

    function renderQueue() {

        const r = refs();
        const api = player();

        if (!r || !api) {
            return;
        }

        const queue = api.getQueue();

        r.queueList.textContent = '';

        queue.forEach(function (song) {

            const row = document.createElement('button');

            row.type = 'button';
            row.className = 'fp-queue-item';
            row.dataset.songId = String(song.id);

            if (String(song.id) === view.songId) {
                row.classList.add('is-current');
            }

            const art = document.createElement('span');

            art.className = 'fp-queue-art';

            if (song.cover) {

                const img = document.createElement('img');

                img.src = song.cover;
                img.alt = '';
                img.loading = 'lazy';

                art.appendChild(img);

            } else {

                art.textContent = '♫';
            }

            const text = document.createElement('span');

            text.className = 'fp-queue-text';

            const title = document.createElement('b');
            const artist = document.createElement('small');

            title.textContent = song.title;
            artist.textContent = song.artist;

            text.appendChild(title);
            text.appendChild(artist);

            row.appendChild(art);
            row.appendChild(text);

            r.queueList.appendChild(row);
        });
    }

    function openQueue() {

        const r = refs();

        renderQueue();

        r.queue.removeAttribute('inert');
        r.queue.setAttribute('aria-hidden', 'false');
        r.queue.classList.add('is-open');
    }

    function closeQueue() {

        const r = refs();

        if (!r) {
            return;
        }

        r.queue.classList.remove('is-open');
        r.queue.setAttribute('aria-hidden', 'true');
        r.queue.setAttribute('inert', '');
    }


    /*
    |--------------------------------------------------------------------------
    | EVENTS (delegated, registered once)
    |--------------------------------------------------------------------------
    */

    function onClick(event) {

        const target = event.target;

        if (!target || !target.closest) {
            return;
        }

        /* Open from the mini player (cover or title). */

        if (target.closest('#playerInfo')) {
            openPlayer();
            return;
        }

        if (!view.open) {
            return;
        }

        const api = player();

        const dot = target.closest('[data-fp-view]');

        if (dot) {
            setPage(dot.dataset.fpView);
            return;
        }

        /* Click outside the menu closes it. */

        if (!target.closest('#fpMenu') && !target.closest('#fpMore')) {
            closeMenu();
        }

        if (target.closest('#fpClose'))      { closePlayer(); return; }
        if (target.closest('#fpPlay'))       { api && api.toggle(); return; }
        if (target.closest('#fpNext'))       { api && api.next(); return; }
        if (target.closest('#fpPrev'))       { api && api.previous(); return; }
        if (target.closest('#fpShuffle'))    { api && api.toggleShuffle(); return; }
        if (target.closest('#fpRepeat'))     { api && api.toggleRepeat(); return; }
        if (target.closest('#fpMute'))       { api && api.toggleMute(); return; }
        if (target.closest('#fpFav'))        { toggleFavorite(); return; }
        if (target.closest('#fpMore'))       { toggleMenu(); return; }
        if (target.closest('#fpQueueBtn'))   { openQueue(); return; }
        if (target.closest('#fpQueueClose')) { closeQueue(); return; }

        const playlistItem = target.closest('.fp-menu-item');

        if (playlistItem) {
            addToPlaylist(playlistItem.dataset.playlistId);
            return;
        }

        const queueItem = target.closest('.fp-queue-item');

        if (queueItem && api) {
            api.playById(queueItem.dataset.songId);
            return;
        }
    }

    function onKeyDown(event) {

        const target = event.target;

        /* Keyboard access to the mini player trigger. */

        if (
            (event.key === 'Enter' || event.key === ' ') &&
            target && target.id === 'playerInfo'
        ) {
            event.preventDefault();
            openPlayer();
            return;
        }

        if (!view.open) {
            return;
        }

        const api = player();
        const onSlider = target && target.id === 'fpTrack';
        const onControl = target && target.closest &&
            target.closest('button, input, textarea, select');

        if (event.key === 'Escape') {

            event.preventDefault();

            const r = refs();

            if (r.menu.classList.contains('is-open')) {
                closeMenu();
            } else if (r.queue.classList.contains('is-open')) {
                closeQueue();
            } else {
                closePlayer();
            }

            return;
        }

        if (onSlider && api) {

            const state = api.getState();
            const step = event.key === 'ArrowRight' || event.key === 'ArrowUp' ? 5
                : event.key === 'ArrowLeft' || event.key === 'ArrowDown' ? -5
                : 0;

            if (step) {

                event.preventDefault();

                api.seek(Math.max(0, Math.min(state.duration || 0, state.currentTime + step)));
            }

            return;
        }

        if (event.key === ' ' && !onControl && api) {
            event.preventDefault();
            api.toggle();
            return;
        }

        if (event.key === 'ArrowRight' && !onControl) {
            setPage('lyrics');
            return;
        }

        if (event.key === 'ArrowLeft' && !onControl) {
            setPage('player');
        }
    }

    function bindSwipe() {

        let startX = 0;
        let startY = 0;
        let tracking = false;

        document.addEventListener('touchstart', function (event) {

            const inside = event.target.closest && event.target.closest('#fpViews');

            tracking = !!(view.open && inside && event.touches.length === 1);

            if (tracking) {
                startX = event.touches[0].clientX;
                startY = event.touches[0].clientY;
            }
        }, { passive: true });

        document.addEventListener('touchend', function (event) {

            if (!tracking) {
                return;
            }

            tracking = false;

            const touch = event.changedTouches[0];
            const dx = touch.clientX - startX;
            const dy = touch.clientY - startY;

            if (Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy) * 1.5) {
                setPage(dx < 0 ? 'lyrics' : 'player');
            }
        }, { passive: true });
    }

    document.addEventListener('click', onClick);
    document.addEventListener('keydown', onKeyDown);

    bindSeek();
    bindLyricsInteraction();
    bindSwipe();

    /*
     * Safety net for Turbo: if a page without the overlay replaces this one
     * while it is open, release the scroll lock and stop the render loop.
     */
    document.addEventListener('turbo:load', function () {

        if (!getRoot()) {

            view.open = false;

            stopLoop();

            document.documentElement.classList.remove('fp-lock');
        }
    });

    /* Don't spin while the tab is hidden; resume when it returns. */
    document.addEventListener('visibilitychange', function () {

        if (document.hidden) {
            stopLoop();
        } else if (view.open) {
            startLoop();
        }
    });

    window.WavoFullPlayer = {
        open: openPlayer,
        close: closePlayer,
        showLyrics: function () { setPage('lyrics'); },
        showPlayer: function () { setPage('player'); },
        isOpen: function () { return view.open; },
        parseLyrics: parseLyrics
    };

})();
</script>
