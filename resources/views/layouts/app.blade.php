{{-- Modul 1 - Auth, Role Access, dan Dashboard Awal --}}
{{-- Ringkas: layout utama aplikasi. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Ticketing Laboran')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2563eb;
            --sidebar-dark: #1e293b;
            --sidebar-hover: #334155;
            --light-bg: #f8fafc;
            --border-light: #e2e8f0;
        }

        body {
            background-color: var(--light-bg);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-dark);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            padding-top: 0;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--sidebar-hover);
            margin-bottom: 1.5rem;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 1rem;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: var(--sidebar-hover);
            color: white;
            padding-left: 1.25rem;
        }

        .sidebar-menu a.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar-menu i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid var(--sidebar-hover);
            background-color: rgba(0, 0, 0, 0.2);
        }

        .sidebar-footer .logout-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #dc2626;
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-footer .logout-btn:hover {
            background-color: #991b1b;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR */
        .navbar-custom {
            background-color: white;
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
            margin: 0;
        }

        .user-role {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
        }

        /* CONTENT AREA */
        .content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                padding: 1.5rem;
            }

            .navbar-user {
                gap: 0.5rem;
            }

            .user-details {
                display: none;
            }
        }

        /* SCROLLBAR STYLING */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--sidebar-dark);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--sidebar-hover);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
    @yield('extra_css')
</head>
<body>
    <div class="main-wrapper">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-brand">
                    <i class="bi bi-ticket-detailed"></i>
                    <span>Ticketing</span>
                </a>
            </div>

            <ul class="sidebar-menu">
                @php
                    $role = auth()->user()->role->nama_role;
                @endphp

                <!-- Menu Admin -->
                @if ($role === 'Admin')
                    <li>
                        <a href="{{ url('/admin/dashboard') }}" class="@if (request()->is('admin/dashboard')) active @endif">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/keluhan') }}" class="@if (request()->is('admin/keluhan*')) active @endif">
                            <i class="bi bi-chat-left-text"></i>
                            <span>Keluhan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/tickets') }}" class="@if (request()->is('admin/tickets*')) active @endif">
                            <i class="bi bi-ticket"></i>
                            <span>Ticket</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/verifikasi') }}" class="@if (request()->is('admin/verifikasi*')) active @endif">
                            <i class="bi bi-check-circle"></i>
                            <span>Verifikasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/laporan') }}" class="@if (request()->is('admin/laporan*')) active @endif">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profile') }}" class="@if (request()->is('profile')) active @endif">
                            <i class="bi bi-person-circle"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                @endif

                <!-- Menu SPV -->
                @if ($role === 'SPV')
                    <li>
                        <a href="{{ url('/spv/dashboard') }}" class="@if (request()->is('spv/dashboard')) active @endif">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/spv/tickets') }}" class="@if (request()->is('spv/tickets*')) active @endif">
                            <i class="bi bi-graph-up"></i>
                            <span>Monitoring Ticket</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/spv/eskalasi') }}" class="@if (request()->is('spv/eskalasi*')) active @endif">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span>Eskalasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/spv/verifikasi') }}" class="@if (request()->is('spv/verifikasi*')) active @endif">
                            <i class="bi bi-check-circle"></i>
                            <span>Verifikasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/spv/laporan') }}" class="@if (request()->is('spv/laporan*')) active @endif">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/spv/users') }}" class="@if (request()->is('spv/users*')) active @endif">
                            <i class="bi bi-people"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/spv/kategori') }}" class="@if (request()->is('spv/kategori*')) active @endif">
                            <i class="bi bi-list-ul"></i>
                            <span>Kategori Masalah</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profile') }}" class="@if (request()->is('profile')) active @endif">
                            <i class="bi bi-person-circle"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                @endif

                <!-- Menu Teknisi -->
                @if ($role === 'Teknisi')
                    <li>
                        <a href="{{ url('/teknisi/dashboard') }}" class="@if (request()->is('teknisi/dashboard')) active @endif">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/teknisi/tickets') }}" class="@if (request()->is('teknisi/tickets*')) active @endif">
                            <i class="bi bi-ticket"></i>
                            <span>Ticket Saya</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/teknisi/riwayat-pengerjaan') }}" class="@if (request()->is('teknisi/riwayat-pengerjaan*')) active @endif">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat Pengerjaan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/teknisi/status-saya') }}" class="@if (request()->is('teknisi/status-saya*')) active @endif">
                            <i class="bi bi-bar-chart"></i>
                            <span>Status Saya</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profile') }}" class="@if (request()->is('profile')) active @endif">
                            <i class="bi bi-person-circle"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- NAVBAR -->
            <div class="navbar-custom">
                <div class="navbar-content">
                    <h5 class="navbar-title">Sistem Ticketing Laboran</h5>
                    <div class="navbar-user">
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <p class="user-name">{{ auth()->user()->name }}</p>
                                <p class="user-role">{{ auth()->user()->role->nama_role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra_js')
</body>
</html>
