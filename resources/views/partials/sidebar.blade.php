@php
    $isSearch   = request()->routeIs('search', 'search.suggest', 'songs.index', 'artists.index');
    $isHome     = request()->routeIs('home', 'home.favorite', 'home.played', 'dashboard');
    $isCreator  = request()->routeIs('creator', 'creator.store', 'creator.destroy');
    $isRadio    = request()->routeIs('radio');
    $isPlaylist = request()->routeIs(
        'playlist.index',
        'playlist.show',
        'playlist.store',
        'playlist.destroy',
        'playlist.uploadCover',
        'playlist.addSong',
        'playlist.removeSong'
    );
    $isFav      = request()->routeIs('favorites');
@endphp

<aside class="sidebar" id="sidebar" data-turbo-permanent>

    {{-- ==========================================================
         BRAND
    ========================================================== --}}

    <a href="{{ route('home') }}" class="brand">
        <span class="brand-logo">〽</span>
        <span class="brand-text">Music</span>
    </a>


    {{-- ==========================================================
         MAIN NAVIGATION
    ========================================================== --}}

    <nav class="sidebar-nav">

        {{-- SEARCH --}}
        <a
            href="{{ route('search') }}"
            class="nav-link {{ $isSearch ? 'active' : '' }}"
            data-nav="search"
        >
            <span class="nav-icon">⌕</span>
            <span>Search</span>
        </a>


        {{-- HOME --}}
        <a
            href="{{ route('home') }}"
            class="nav-link {{ $isHome ? 'active' : '' }}"
            data-nav="home"
        >
            <span class="nav-icon">⌂</span>
            <span>Home</span>
        </a>


        {{-- CREATOR --}}
        <a
            href="{{ route('creator') }}"
            class="nav-link {{ $isCreator ? 'active' : '' }}"
            data-nav="creator"
        >
            <span class="nav-icon">▦</span>
            <span>Creator</span>
        </a>


        {{-- RADIO --}}
        <a
            href="{{ route('radio') }}"
            class="nav-link {{ $isRadio ? 'active' : '' }}"
            data-nav="radio"
        >
            <span class="nav-icon">◉</span>
            <span>Radio</span>
        </a>

    </nav>


    {{-- ==========================================================
         LIBRARY
    ========================================================== --}}

    @auth

        <div class="library-title">
            Library
        </div>


        {{-- PLAYLIST --}}
        <a
            href="{{ route('playlist.index') }}"
            class="library-item {{ $isPlaylist ? 'active' : '' }}"
            data-nav="playlist"
        >
            <div class="library-thumb">
                ▶
            </div>

            <span>
                Hot Play
            </span>
        </a>


        {{-- FAVOURITE --}}
        <a
            href="{{ route('favorites') }}"
            class="library-item {{ $isFav ? 'active' : '' }}"
            data-nav="favorites"
        >
            <div class="library-thumb round">
                ♥
            </div>

            <span>
                Favourite
            </span>
        </a>

    @endauth


    {{-- ==========================================================
         PROFILE
    ========================================================== --}}

    @auth

        <a
            href="{{ route('profile.show') }}"
            class="profile"
        >

                        <div
                class="profile-avatar"
                data-user-avatar="{{ Auth::id() }}"
                data-image-alt="{{ Auth::user()->name }}"
            >

                @if(Auth::user()->profile_photo_url)

                    <img
                        src="{{ Auth::user()->profile_photo_url }}"
                        alt=""
                    >

                @else

                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                @endif

            </div>


            <div class="profile-name">
                {{ Auth::user()->name }}
            </div>

        </a>

    @else

        <a
            href="{{ route('login') }}"
            class="profile"
        >

            <div class="profile-avatar">
                ?
            </div>

            <div class="profile-name">
                Login
            </div>

        </a>

    @endauth

    <script src="{{ asset('js/wavo-images.js') }}"></script>
    
</aside>

{{-- ==========================================================
     MOBILE CHROME  (hanya tampil <= 820px, diatur wavo-shell.css)
     Sidebar desktop disembunyikan di mobile dan diganti:
       - header atas  : brand + avatar profil
       - tab bar bawah: navigasi utama
========================================================== --}}

<header class="mobile-header">

    <a href="{{ route('home') }}" class="brand">
        <span class="brand-logo">〽</span>
        <span class="brand-text">Music</span>
    </a>

    @auth
        <a href="{{ route('profile.show') }}" class="mobile-profile" aria-label="Profile">
            <div
                class="profile-avatar"
                data-user-avatar="{{ Auth::id() }}"
                data-image-alt="{{ Auth::user()->name }}"
            >
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>
            <span class="profile-name">{{ Auth::user()->name }}</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="mobile-profile">
            <div class="profile-avatar">?</div>
            <span class="profile-name">Login</span>
        </a>
    @endauth

</header>

<nav class="mobile-nav" aria-label="Main navigation">

    <a href="{{ route('home') }}" class="m-nav-item {{ $isHome ? 'active' : '' }}">
        <span class="m-nav-icon">⌂</span><span>Home</span>
    </a>

    <a href="{{ route('search') }}" class="m-nav-item {{ $isSearch ? 'active' : '' }}">
        <span class="m-nav-icon">⌕</span><span>Search</span>
    </a>

    <a href="{{ route('radio') }}" class="m-nav-item {{ $isRadio ? 'active' : '' }}">
        <span class="m-nav-icon">◉</span><span>Radio</span>
    </a>

    @auth
        <a href="{{ route('playlist.index') }}" class="m-nav-item {{ $isPlaylist ? 'active' : '' }}">
            <span class="m-nav-icon">▶</span><span>Playlist</span>
        </a>

        <a href="{{ route('favorites') }}" class="m-nav-item {{ $isFav ? 'active' : '' }}">
            <span class="m-nav-icon">♥</span><span>Favorite</span>
        </a>

        <a href="{{ route('creator') }}" class="m-nav-item {{ $isCreator ? 'active' : '' }}">
            <span class="m-nav-icon">▦</span><span>Creator</span>
        </a>
    @endauth

</nav>
