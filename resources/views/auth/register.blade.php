<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Wavo Music</title>

    <script src="https://unpkg.com/@hotwired/turbo@8.0.0/dist/turbo.es2017-umd.js"></script>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { min-height: 100%; }

        /* Di-scope ke body.auth-body supaya aturan ini TIDAK bocor ke halaman lain.
           Turbo Drive menyimpan <style> halaman sebelumnya di <head>, sehingga
           selector `body` polos akan tetap aktif di Home setelah navigasi. */
        body.auth-body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0a0a0a;
            color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* =====================================================
           MAIN AREA
        ===================================================== */
        .auth-page {
            flex: 1;
            background: linear-gradient(135deg, #c9a24a 0%, #8b6f2d 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            position: relative;
        }

        .auth-page::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.12) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0,0,0,0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 560px;
            background: rgba(90, 70, 35, 0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 44px 48px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        /* Brand */
        .auth-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 18px;
        }
        .auth-brand-logo {
            color: #c5a45c;
            font-size: 22px;
            line-height: 1;
        }
        .auth-brand-text {
            color: #f5f5f5;
            font-size: 15px;
            font-weight: 600;
        }

        /* Small title */
        .auth-title-sm {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
        }

        /* Main title */
        .auth-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 14px;
        }

        /* Subtitle */
        .auth-subtitle {
            text-align: center;
            font-size: 12px;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.75);
            max-width: 400px;
            margin: 0 auto 28px;
        }

        /* Form row */
        .form-row {
            display: grid;
            grid-template-columns: 100px 1fr;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            text-align: right;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            height: 38px;
            padding: 0 14px;
            background: rgba(180, 165, 130, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 13px;
            outline: none;
            transition: border-color .15s, background .15s, box-shadow .15s;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-input:focus {
            background: rgba(180, 165, 130, 0.7);
            border-color: #c5a45c;
            box-shadow: 0 0 0 3px rgba(197, 164, 92, 0.25);
        }

        .form-error {
            color: #ff8a8a;
            font-size: 11px;
            margin-top: 4px;
            margin-left: 116px;
        }

        /* Checkbox */
        .auth-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 22px;
            margin-left: 116px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 11px;
            line-height: 1.4;
            cursor: pointer;
        }

        .auth-check input {
            width: 14px;
            height: 14px;
            margin-top: 2px;
            flex-shrink: 0;
            accent-color: #c5a45c;
            cursor: pointer;
        }

        .auth-check a {
            color: #fff;
            text-decoration: underline;
        }

        /* Submit */
        .auth-submit-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 26px;
        }

        .auth-submit {
            min-width: 140px;
            height: 38px;
            padding: 0 26px;
            background: #d9b56b;
            border: none;
            border-radius: 8px;
            color: #2c2110;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s, transform .1s;
        }

        .auth-submit:hover {
            background: #e5c37b;
        }

        .auth-submit:active {
            transform: scale(0.98);
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Social */
        .social-row {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 26px;
        }

        .social-btn {
            flex: 0 0 140px;
            height: 46px;
            background: rgba(230, 225, 210, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .15s, transform .1s;
        }

        .social-btn:hover {
            background: rgba(240, 235, 220, 0.9);
        }

        .social-btn:active {
            transform: scale(0.98);
        }

        .social-btn svg {
            width: 22px;
            height: 22px;
        }

        /* Footer of card */
        .auth-bottom {
            text-align: center;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        .auth-bottom a {
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
        }

        .auth-bottom a:hover {
            text-decoration: underline;
        }

        /* =====================================================
           FOOTER
        ===================================================== */
        .footer {
            background: #4b4b4b;
            color: #aaa;
            padding: 40px 60px 35px;
        }

        .footer-language {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            font-size: 13px;
        }

        .footer-language .active-language {
            color: #eee;
        }

        .footer-divider {
            width: 1px;
            height: 24px;
            background: #aaa;
        }

        .footer-copy {
            margin-bottom: 30px;
            font-size: 13px;
        }

        .footer-copy strong {
            color: #eee;
            font-weight: 500;
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 13px;
        }

        .footer-link-divider {
            width: 1px;
            height: 24px;
            background: #999;
        }

        .footer-links a:hover {
            color: #eee;
        }

        @media (max-width: 640px) {
            .auth-card {
                padding: 32px 24px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            .form-label {
                text-align: left;
            }

            .form-error,
            .auth-check {
                margin-left: 0;
            }

            .social-btn {
                flex: 1;
            }

            .footer {
                padding: 30px 25px 25px;
            }

            .footer-links {
                flex-wrap: wrap;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/wavo-shell.css') }}">
</head>

<body class="auth-body">

<div class="auth-page">

    <div class="auth-card">

        {{-- BRAND --}}
        <div class="auth-brand">
            <span class="auth-brand-logo">〽</span>
            <span class="auth-brand-text">Music</span>
        </div>

        {{-- SMALL TITLE --}}
        <div class="auth-title-sm">Create Account</div>

        {{-- TITLE --}}
        <h1 class="auth-title">Join the Sound Revolution</h1>

        {{-- SUBTITLE --}}
        <p class="auth-subtitle">
            Create an account to save your favorite tracks, build playlists, and discover new creators.
        </p>

        {{-- ERROR GLOBAL --}}
        @if ($errors->any())
            <div style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f4d6d6; padding: 10px 14px; border-radius: 8px; font-size: 12px; margin-bottom: 18px;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="form-row">
                <label for="email" class="form-label">Email:</label>
                <div class="form-input-wrapper">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input"
                        placeholder="name@example.com"
                        required
                        autocomplete="username"
                    >
                </div>
            </div>
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror

            {{-- USERNAME --}}
            <div class="form-row">
                <label for="name" class="form-label">Username:</label>
                <div class="form-input-wrapper">
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-input"
                        placeholder="@wavofan"
                        required
                        autocomplete="name"
                    >
                </div>
            </div>
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror

            {{-- PASSWORD --}}
            <div class="form-row">
                <label for="password" class="form-label">Password:</label>
                <div class="form-input-wrapper">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-input"
                        placeholder="Must be at least 8 characters"
                        required
                        autocomplete="new-password"
                    >
                </div>
            </div>
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror

            {{-- CHECKBOX --}}
            <label class="auth-check">
                <input type="checkbox" name="agree" required>
                <span>
                    By signing up, you agree to Wavo's
                    <a href="#">Terms of Service</a> and
                    <a href="#">Privacy Policy</a>.
                </span>
            </label>

            @error('agree')
    <div class="text-red-400 text-sm mt-2">
        {{ $message }}
    </div>
@enderror

            {{-- SUBMIT --}}
            <div class="auth-submit-wrapper">
                <button type="submit" class="auth-submit">Get Started</button>
            </div>

        </form>

        {{-- DIVIDER --}}
        <div class="auth-divider">Or continue with</div>

        {{-- SOCIAL --}}
        <div class="social-row">

            {{-- Apple --}}
            <button type="button" class="social-btn" title="Continue with Apple">
                <svg viewBox="0 0 24 24" fill="currentColor" style="color: #1a1a1a;">
                    <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"></path>
                </svg>
            </button>

            {{-- Google --}}
            <button type="button" class="social-btn" title="Continue with Google">
                <svg viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
            </button>

        </div>

        {{-- BOTTOM LINK --}}
        <p class="auth-bottom">
            Already have an account?
            <a href="{{ route('login') }}">Sign in</a>
        </p>

    </div>

</div>


{{-- FOOTER --}}
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

</body>
</html>