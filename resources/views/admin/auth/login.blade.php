@extends('admin.layouts.auth')

@section('content')
<div class="login-card">

    {{-- Logo & Badge --}}
    <div class="flex flex-col items-center mb-8">
        {{-- Logo Icon --}}
        <div style="width:52px;height:52px;background:linear-gradient(135deg,#6366f1,#7c3aed);border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;box-shadow:0 8px 24px rgba(99,102,241,0.45);">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;color:white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </div>

        {{-- Admin Badge --}}
        <div class="admin-badge">
            <span class="dot"></span>
            Admin Portal
        </div>

        <h1 style="font-size:1.4rem;font-weight:700;color:#ffffff;letter-spacing:-0.02em;text-align:center;margin:0;line-height:1.3;">
            Sign in to Dashboard
        </h1>
        <p style="font-size:0.8rem;color:rgba(160,160,190,0.7);margin-top:0.375rem;text-align:center;">
            Restricted access — admins only
        </p>
    </div>

    {{-- Status Message --}}
    @if (session('status'))
        <div class="status-msg">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation Errors (global) --}}
    @if ($errors->any())
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:0.5rem;padding:0.625rem 0.875rem;font-size:0.78rem;color:#fca5a5;margin-bottom:1.25rem;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Login Form --}}
    <form action="{{ route('admin.login.submit') }}" method="POST" style="display:flex;flex-direction:column;gap:1.125rem;">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="form-label">Email address</label>
            <input
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                required
                autofocus
                placeholder="admin@example.com"
                value="{{ old('email') }}"
                class="form-input"
            >
            @error('email')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                <label for="password" class="form-label" style="margin-bottom:0;">Password</label>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>
            <div style="position:relative;">
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                    placeholder="••••••••"
                    class="form-input"
                    style="padding-right:2.75rem;"
                >
                {{-- Toggle password visibility --}}
                <button
                    type="button"
                    id="togglePassword"
                    onclick="togglePwd()"
                    style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:rgba(150,150,175,0.6);padding:0;display:flex;align-items:center;"
                    aria-label="Toggle password visibility"
                >
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div style="margin-top:0.375rem;">
            <button type="submit" class="btn-signin" id="signinBtn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Sign in to Admin Panel
            </button>
        </div>
    </form>

    <hr class="divider">

    {{-- Footer link --}}
    <p style="text-align:center;font-size:0.78rem;color:rgba(140,140,165,0.7);margin:0;">
        Not an admin?
        <a href="{{ route('login') }}" class="user-login-link" style="margin-left:0.25rem;">Go to user login →</a>
    </p>
</div>

<script>
function togglePwd() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>
@endsection
