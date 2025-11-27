@extends('layouts.app')

@section('title', 'Welcome Back - TokoKita')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }

    /* Membuat background mesh gradient mirip gambar referensi */
    .login-container {
        height: 100vh; /* Mengganti min-height: 100vh menjadi height: 100% */
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        /* Gradasi biru-ungu halus */
        background:
            radial-gradient(at 0% 0%, hsla(253,16%,7%,0) 0, transparent 50%),
            radial-gradient(at 50% 0%, hsla(225,39%,30%,0) 0, transparent 50%),
            radial-gradient(at 100% 0%, hsla(339,49%,30%,0) 0, transparent 50%);
        background-image: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
        background-size: cover;
    }

    /* Layer tambahan untuk efek blur/mesh yang lebih estetik */
    .login-container::before {
        content: "";
        position: absolute;
        top: -10%;
        left: -10%;
        width: 120%;
        height: 120%;
        background: radial-gradient(circle, rgba(142,158,255,0.4) 0%, rgba(255,255,255,0) 70%);
        z-index: 0;
        pointer-events: none;
    }

    .login-card {
        background: #ffffff;
        width: 100%;
        max-width: 450px; /* Ukuran kartu */
        padding: 3rem;
        border-radius: 24px; /* Sudut sangat membulat sesuai gambar */
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); /* Shadow lembut */
        position: relative;
        z-index: 1;
        text-align: center;
    }

    /* Logo kecil di atas */
    .brand-header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        color: #555;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }

    .brand-header i {
        margin-right: 8px;
        font-size: 1.2rem;
        color: #5d5fef; /* Warna logo */
    }

    .welcome-text {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111;
        margin-bottom: 0.5rem;
    }

    .subtitle-text {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    /* Styling Form Input agar clean */
    .form-group {
        margin-bottom: 1.5rem;
        text-align: left;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        display: block;
    }

    .custom-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        font-size: 0.95rem;
        color: #333;
        transition: all 0.3s;
        background: #fff;
    }

    .custom-input:focus {
        border-color: #5d5fef;
        outline: none;
        box-shadow: 0 0 0 3px rgba(93, 95, 239, 0.1);
    }

    .custom-input::placeholder {
        color: #ccc;
    }

    /* Wrapper untuk password agar icon mata bisa masuk */
    .password-wrapper {
        position: relative;
    }

    .toggle-password-btn {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #999;
    }

    .toggle-password-btn:hover {
        color: #5d5fef;
    }

    /* Checkbox & Forgot Password Row */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        font-size: 0.85rem;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-check-input {
        width: 16px;
        height: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
    }

    .forgot-link {
        color: #5d5fef;
        text-decoration: none;
        font-weight: 500;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    /* Tombol Utama */
    .btn-submit {
        width: 100%;
        padding: 14px;
        background-color: #5d5fef; /* Warna biru ungu sesuai gambar */
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background-color: #4b4dcf;
    }

    .btn-submit:disabled {
        background-color: #a0a1e3;
        cursor: not-allowed;
    }

</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">

        <div class="brand-header">
            <i class="fas fa-cube"></i> TOKOKITA
        </div>

        <h2 class="welcome-text">Welcome Back!</h2>
        <p class="subtitle-text">We missed you! Please enter your details.</p>

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email"
                    class="custom-input @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}"
                    required autofocus placeholder="Enter your email">
                @error('email')
                    <small style="color: red; font-size: 0.8rem; display: block; margin-top: 5px;">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input id="password" type="password"
                        class="custom-input @error('password') is-invalid @enderror"
                        name="password" required placeholder="Enter password">

                    <button type="button" class="toggle-password-btn" id="togglePassword">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                @error('password')
                    <small style="color: red; font-size: 0.8rem; display: block; margin-top: 5px;">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="form-actions">
                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" style="color: #666; cursor: pointer;">Remember me</label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                Sign in
            </button>

            </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Password Toggle Logic
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const icon = toggleBtn.querySelector('i');

    toggleBtn.addEventListener('click', function() {
        // Toggle type
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle icon class
        if (type === 'password') {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // 2. Loading State Animation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'Signing in...';
        });
    }
});
</script>
@endsection
