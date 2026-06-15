{{-- Modul 1 - Auth, Role Access, dan Dashboard Awal --}}
{{-- Ringkas: halaman login user. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Ticketing Laboran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
        }

        .login-box {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 2rem;
            text-align: center;
            color: white;
        }

        .login-header .system-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            margin: 0;
        }

        .login-body {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-check {
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            border: 2px solid #e2e8f0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            color: #475569;
            font-size: 0.9rem;
            cursor: pointer;
            margin-left: 0.5rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            border-top: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .login-footer p {
            margin: 0;
            color: #64748b;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.3rem;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 0.5rem;
            border: 2px solid #fecaca;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.85rem;
            color: #dc2626;
        }

        .form-control.is-invalid {
            border-color: #dc2626;
        }

        .btn-google-sso {
            width: 100%;
            padding: 0.75rem;
            background: white;
            color: #475569;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-google-sso:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            color: #1e293b;
        }

        .btn-google-sso:active {
            transform: translateY(0);
        }

        .google-icon {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="system-icon">
                    <i class="bi bi-ticket-detailed"></i>
                </div>
                <h1>Sistem Ticketing Laboran</h1>
                <p>Kelola keluhan, ticket, dan maintenance laboran</p>
            </div>

            <div class="login-body">
                @if ($errors->any() || session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Login Gagal!</strong>
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
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            placeholder="nama@example.com"
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
                            placeholder="Masukkan password"
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
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>

                <div class="text-center my-3 position-relative">
                    <span class="text-muted text-sm px-3 bg-white" style="z-index: 2; position: relative;">atau</span>
                    <hr class="w-100 position-absolute start-0 top-50 translate-middle-y m-0" style="z-index: 1; border-color: #e2e8f0;">
                </div>

                <a href="{{ route('google.redirect') }}" class="btn-google-sso">
                    <svg class="google-icon me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20px" height="20px">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                        <path fill="#1976D2" d="M43.611,20.083H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                    </svg>
                    Login dengan Google
                </a>
            </div>

            <div class="login-footer">
                <p>
                    Belum memiliki akun? Hubungi SPV.
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
