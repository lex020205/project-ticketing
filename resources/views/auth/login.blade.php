{{-- Modul 1 - Auth, Role Access, dan Dashboard Awal --}}
{{-- Ringkas: halaman login user. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Ticketing Laboran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #ffffff;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --border: #e2e8f0;
            --border-strong: #cbd5e1;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #2563eb;
            --danger: #dc2626;
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .auth-form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #ffffff;
        }

        .auth-form-inner {
            width: 100%;
            max-width: 420px;
        }

        .auth-title {
            margin: 0 0 0.45rem;
            font-size: clamp(1.8rem, 2.8vw, 2.6rem);
            font-weight: 800;
            letter-spacing: -0.05em;
            line-height: 1.08;
        }

        .auth-subtitle {
            margin: 0 0 1.5rem;
            color: var(--muted);
            font-size: 0.98rem;
            line-height: 1.6;
        }

        .auth-form-header {
            margin-bottom: 1.4rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #334155;
            font-size: 0.92rem;
        }

        .form-control {
            padding: 0.78rem 0.95rem;
            border: 1px solid var(--border);
            border-radius: 14px;
            font-size: 0.95rem;
            background: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--border-strong);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        }

        .form-check {
            margin: 1rem 0 1.25rem;
        }

        .form-check-input {
            border: 1px solid var(--border-strong);
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            color: #475569;
            font-size: 0.9rem;
            cursor: pointer;
            margin-left: 0.35rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem 1rem;
            background: #0f172a;
            color: white;
            border: 1px solid #0f172a;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #111827;
            border-color: #111827;
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.15rem 0;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            height: 1px;
            background: var(--border);
            flex: 1;
        }

        .btn-google-sso {
            width: 100%;
            padding: 0.8rem 1rem;
            background: #ffffff;
            color: #334155;
            border: 1px solid var(--border);
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.98rem;
            transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-google-sso:hover {
            background: #f8fafc;
            border-color: var(--border-strong);
            transform: translateY(-1px);
            color: #0f172a;
        }

        .google-icon {
            display: inline-block;
        }

        .auth-visual {
            position: relative;
            overflow: hidden;
            background: #ffffff url('{{ asset('images/logo-noc.png') }}') center center / contain no-repeat;
        }

        .auth-visual-content {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            display: block;
            padding: 0;
        }

        .alert {
            border-radius: 14px;
            border: 1px solid #fecaca;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.85rem;
            color: var(--danger);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        @media (max-width: 991px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .auth-visual {
                min-height: 34vh;
                border-bottom: 1px solid var(--border);
            }

            .auth-form-side {
                padding: 1.25rem;
            }
        }

        @media (max-width: 576px) {
            .auth-visual,
            .auth-form-side {
                padding: 0.85rem;
            }

            .auth-form-inner {
                max-width: 100%;
            }

            .auth-title {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <section class="auth-form-side">
            <div class="auth-form-inner">
                <div class="auth-form-header">
                    <h1 class="auth-title">Login To Your Account</h1>
                    <p class="auth-subtitle">Welcome back. Masuk untuk mengakses dashboard sesuai role Anda.</p>
                </div>

                @if ($errors->any() || session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Login gagal.</strong>
                        @if (session('error'))
                            <div>{{ session('error') }}</div>
                        @endif
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email or username</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            placeholder="Enter your email or username"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="••••••••••••"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="remember"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn-login">Log in</button>
                </form>

                <div class="divider">or</div>

                <a href="{{ route('google.redirect') }}" class="btn-google-sso">
                    <svg class="google-icon me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20px" height="20px">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                        <path fill="#1976D2" d="M43.611,20.083H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                    </svg>
                    Login with Google
                </a>

            </div>
        </section>

        <section class="auth-visual d-none d-lg-block">
            <div class="auth-visual-content"></div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
