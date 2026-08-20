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
        <p class="login-subtitle">Use your account to continue.</p>

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
                            aria-label="Show password">Show</button>
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
        display: flex; flex-direction: column; align-items: center; gap: 10px;
        margin-bottom: 22px;
    }
    .login-brand img { width: 64px; height: 64px; object-fit: contain; display: block; }
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
    .login-password { position: relative; display: flex; }
    .login-password input { padding-right: 64px !important; }
    .login-reveal {
        position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
        background: transparent; border: none; cursor: pointer;
        font-size: 12px; font-weight: 500; color: var(--muted, #6B7280);
        padding: 6px 8px; border-radius: 6px;
    }
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
        .login-brand img { width: 56px; height: 56px; }
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
        btn.textContent = shown ? 'Show' : 'Hide';
        btn.setAttribute('aria-label', shown ? 'Show password' : 'Hide password');
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
