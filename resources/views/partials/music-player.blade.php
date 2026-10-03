{{--
|--------------------------------------------------------------------------
| MUSIC PLAYER PARTIAL
|--------------------------------------------------------------------------
| CSS-nya di public/css/player.css
|--
--}}

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