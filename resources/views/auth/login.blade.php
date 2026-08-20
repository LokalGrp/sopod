@extends('layouts.guest')
@section('title', 'Sign in')

@section('content')
@php
    // Inline the logo so it renders identically wherever the app is hosted.
    // Prefers the standalone Meatplus mark; falls back to the combined image.
    $logoBase64 = '';
    foreach (['images/meatplus-logo.png', 'images/sopod-logo.png'] as $candidate) {
        $path = public_path($candidate);
        if (file_exists($path)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
            break;
        }
    }
@endphp

<div class="login-page">
    <div class="login-card">

        <div class="login-brand">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Meatplus Trading Corporation">
            @endif
            <span class="login-wordmark">NOMSUITE</span>
        </div>

        <h1 class="login-title">Sign in to NOMSUITE</h1>
        <p class="login-subtitle">Enter your credentials to continue.</p>

        <form method="POST" action="{{ route('login.submit') }}" class="login-form">
            @csrf

            <div class="login-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@meatplus.ph"
                       autocomplete="username" required>
            </div>

            <div class="login-field">
                <label for="password">Password</label>
                <div class="login-password">
                    <input type="password" id="password" name="password" placeholder="Enter your password"
                           autocomplete="current-password" required>
                    <button type="button" class="login-reveal" data-target="password"
                            aria-label="Show password" title="Show password">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                            <path d="M10.6 6.2A9.9 9.9 0 0 1 12 6c6.4 0 10 7 10 7a17 17 0 0 1-2.7 3.6M6.6 6.6A17 17 0 0 0 2 13s3.6 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/>
                            <path d="m2 2 20 20"/>
                        </svg>
                    </button>
                </div>
            </div>

            @if ($errors->any())
                <div class="login-alert login-alert-error" role="alert">
                    @if($errors->has('TokenMismatchException') || session('message') == 'CSRF token mismatch.')
                        Your session has expired. Please refresh the page and try again.
                    @else
                        {{ $errors->first() }}
                    @endif
                </div>
            @endif

            @if (session('success'))
                <div class="login-alert login-alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <button type="submit" class="login-submit">Sign In</button>
        </form>

        <p class="login-help">
            Need access?
            <a href="http://mtcresolveit.meatplus.ph/public/ticket/index.php?entity=1" target="_blank" rel="noopener">
                Request an account
            </a>
        </p>
    </div>
</div>

<style>
    /* Uses the same tokens as the authenticated interface, defined in
       partials/theme.blade.php, with fallbacks so the page still renders
       correctly if it is ever served without them. */
    .login-page {
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        padding: 24px 16px;
        background: var(--canvas, #F6F8FB);
    }
    .login-card {
        width: 100%; max-width: 440px;
        background: var(--surface, #FFFFFF);
        border: 1px solid var(--line, #E5E7EB);
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(16,24,40,.06);
        padding: 36px 36px 30px;
    }
    .login-brand {
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        margin-bottom: 16px;
    }
    .login-brand img { width: 48px; height: 48px; object-fit: contain; display: block; }
    .login-wordmark {
        font-size: 13px; font-weight: 600; letter-spacing: .16em;
        color: var(--heading, #111827); text-transform: uppercase;
    }
    .login-title {
        font-size: 20px; font-weight: 600; margin: 0;
        color: var(--heading, #111827); text-align: center; letter-spacing: -.01em;
    }
    .login-subtitle {
        font-size: 13px; color: var(--muted, #6B7280);
        text-align: center; margin: 4px 0 24px;
    }
    .login-form { display: flex; flex-direction: column; gap: 16px; }
    .login-field { display: flex; flex-direction: column; gap: 6px; }
    .login-field label {
        font-size: 13px; font-weight: 500; color: var(--heading, #111827); margin: 0;
    }
    .login-field input {
        width: 100%; height: 46px;
        padding: 0 12px !important;
        font-size: 14px !important;
        color: var(--body, #374151);
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
        border-radius: 8px !important;
        transition: border-color .12s ease, box-shadow .12s ease;
    }
    .login-field input::placeholder { color: #9CA3AF; }
    .login-field input:focus {
        outline: none;
        border-color: var(--primary, #2563EB) !important;
        box-shadow: 0 0 0 3px rgba(37,99,235,.12) !important;
    }
    /* Chrome paints saved credentials with its own pale blue background.
       Repaint it white so the blue stays reserved for focus and actions. */
    .login-field input:-webkit-autofill,
    .login-field input:-webkit-autofill:hover,
    .login-field input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--body, #374151);
        -webkit-box-shadow: 0 0 0 1000px #FFFFFF inset;
        box-shadow: 0 0 0 1000px #FFFFFF inset;
        transition: background-color 9999s ease-in-out 0s;
    }

    .login-password { position: relative; display: flex; }
    .login-password input { padding-right: 46px !important; }
    .login-reveal {
        position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
        background: transparent; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; color: #9CA3AF;
        padding: 0; border-radius: 6px;
    }
    .login-reveal svg { width: 17px; height: 17px; }
    .login-reveal:hover { color: var(--primary, #2563EB); background: #F3F4F6; }
    .login-alert {
        font-size: 13px; line-height: 1.45;
        padding: 9px 12px; border-radius: 8px; border: 1px solid transparent;
    }
    .login-alert-error   { background: #FEF2F2; border-color: #FECACA; color: #B91C1C; }
    .login-alert-success { background: #F0FDF4; border-color: #BBF7D0; color: #15803D; }
    .login-submit {
        width: 100%; height: 46px;
        background: var(--primary, #2563EB) !important;
        border: 1px solid var(--primary, #2563EB) !important;
        color: #FFFFFF !important;
        font-size: 14px; font-weight: 600;
        border-radius: 8px !important; cursor: pointer;
        transition: background-color .12s ease;
        margin-top: 2px;
    }
    .login-submit:hover { background: var(--primary-hover, #1D4ED8) !important; }
    .login-submit:focus-visible { outline: 2px solid var(--primary, #2563EB); outline-offset: 2px; }
    .login-help {
        margin: 22px 0 0; text-align: center;
        font-size: 13px; color: var(--muted, #6B7280);
    }
    .login-help a { color: var(--primary, #2563EB); font-weight: 500; text-decoration: none; }
    .login-help a:hover { text-decoration: underline; }
    @media (max-width: 480px) {
        .login-page { padding: 16px; align-items: flex-start; padding-top: 40px; }
        .login-card { padding: 26px 20px 22px; border-radius: 12px; }
        .login-brand img { width: 42px; height: 42px; }
        .login-title { font-size: 18px; }
    }
</style>

<script>
// Show/hide password. Presentation only: it toggles the input type and does
// not touch the field name, value or how the form submits.
document.querySelectorAll('.login-reveal').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = document.getElementById(btn.dataset.target);
        if (!input) return;
        var shown = input.type === 'text';
        input.type = shown ? 'password' : 'text';
        btn.querySelector('.eye-open').style.display = shown ? '' : 'none';
        btn.querySelector('.eye-off').style.display = shown ? 'none' : '';
        var label = shown ? 'Show password' : 'Hide password';
        btn.setAttribute('aria-label', label);
        btn.setAttribute('title', label);
    });
});

// Auto-refresh CSRF token every 30 minutes to prevent expiration
setInterval(function() {
    fetch('{{ route("login") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(response => response.text())
    .then(html => {
        // Extract new CSRF token from response
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newToken = doc.querySelector('input[name="_token"]');
        if (newToken) {
            document.querySelector('input[name="_token"]').value = newToken.value;
        }
    }).catch(err => console.log('Token refresh failed'));
}, 30 * 60 * 1000); // 30 minutes
</script>
@endsection
