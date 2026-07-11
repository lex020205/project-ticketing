{{-- Modul 1 - Auth, Role Access, dan Dashboard Awal --}}
{{-- Ringkas: layout utama aplikasi. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Ticketing Laboran')</title>
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
            --primary-color: #2563eb;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f8fafc;
            --light-bg: #f8fafc;
            --border-light: #e2e8f0;
            --sidebar-text: #334155;
            --sidebar-muted: #64748b;
        }

        body {
            background-color: var(--light-bg);
            color: #0f172a;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 1100;
            border-right: 1px solid var(--border-light);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            margin-bottom: 1.5rem;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.18rem;
            font-weight: 600;
            color: var(--sidebar-text);
            text-decoration: none;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .sidebar-menu-wrapper {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 0 1rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 48px;
            padding: 0.9rem 1rem;
            color: var(--sidebar-muted);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1rem;
            line-height: 1.35;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: var(--sidebar-hover);
            color: var(--sidebar-text);
        }

        .sidebar-menu a.active {
            background-color: #eff6ff;
            color: var(--primary-color);
            font-weight: 600;
        }

        .sidebar-menu i {
            width: 20px;
            text-align: center;
            font-size: 1.08rem;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--border-light);
            background: transparent;
            margin-top: auto;
        }

        .sidebar-footer .logout-btn {
            width: 100%;
            padding: 12px;
            background: #ffffff;
            color: #dc3545;
            border: 1px solid #fecaca;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-footer .logout-btn:hover {
            background: #fef2f2;
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
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02);
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
            font-size: 1.15rem;
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
        /* Tablet: 769px - 1024px */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }

            .main-content {
                margin-left: 240px;
            }

            .sidebar-menu a {
                font-size: 0.98rem;
            }
        }

        /* Small/Mobile devices: max-width 768px */
        @media (max-width: 768px) {
            .sidebar {
                width: 290px;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 1100;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-custom {
                padding: 0.85rem 1rem;
            }

            .navbar-title {
                font-size: 1.05rem;
                line-height: 1.25;
            }

            .content {
                padding: 1.1rem;
            }

            .navbar-user {
                gap: 0.5rem;
            }

            .user-details {
                display: none;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 0.9rem;
            }

            .sidebar-brand {
                font-size: 1.2rem;
            }

            .sidebar-menu a {
                font-size: 1.08rem;
                padding: 0.95rem 1rem;
                min-height: 52px;
                line-height: 1.4;
            }

            .sidebar-menu i {
                width: 22px;
                font-size: 1.1rem;
            }

            .sidebar-menu span {
                word-break: break-word;
            }

            .sidebar-header {
                padding: 1.25rem 1rem;
                margin-bottom: 1rem;
            }

            .sidebar-footer {
                padding: 0.9rem 1rem 1.1rem;
            }

            .sidebar-footer .logout-btn {
                min-height: 50px;
                font-size: 1.02rem;
            }
        }

        /* BACKDROP FOR MOBILE SIDEBAR */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 1050;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* SCROLLBAR STYLING */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--sidebar-bg);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @yield('extra_css')
</head>
<body>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="main-wrapper">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-brand">
                    <i class="bi bi-ticket-detailed"></i>
                    <span>Ticketing</span>
                </a>
            </div>
            <div class="sidebar-menu-wrapper">
                <ul class="sidebar-menu">
                @php
                    $role = auth()->user()?->role?->nama_role;
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

                <!-- Menu Super Admin -->
                @if ($role === 'Super Admin')
                    <li>
                        <a href="{{ url('/super-admin/dashboard') }}" class="@if (request()->is('super-admin/dashboard')) active @endif">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/super-admin/tickets') }}" class="@if (request()->is('super-admin/tickets*')) active @endif">
                            <i class="bi bi-graph-up"></i>
                            <span>Monitoring Ticket</span>
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
                        <a href="{{ url('/spv/eskalasi') }}" class="@if (request()->is('spv/eskalasi*')) active @endif">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span>Eskalasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/spv/users') }}" class="@if (request()->is('spv/users*')) active @endif">
                            <i class="bi bi-people"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/super-admin/roles') }}" class="@if (request()->is('super-admin/roles*')) active @endif">
                            <i class="bi bi-shield-lock"></i>
                            <span>Role Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/spv/kategori') }}" class="@if (request()->is('spv/kategori*')) active @endif">
                            <i class="bi bi-list-ul"></i>
                            <span>Kategori Masalah</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/teknisi/tickets') }}" class="@if (request()->is('teknisi/tickets*')) active @endif">
                            <i class="bi bi-ticket"></i>
                            <span>Ticket Teknisi</span>
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
                            <span>Status Teknisi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/super-admin/laporan') }}" class="@if (request()->is('super-admin/laporan*')) active @endif">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span>Laporan Global</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/super-admin/audit') }}" class="@if (request()->is('super-admin/audit*')) active @endif">
                            <i class="bi bi-journal-text"></i>
                            <span>Audit Log</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/super-admin/settings') }}" class="@if (request()->is('super-admin/settings*')) active @endif">
                            <i class="bi bi-gear"></i>
                            <span>Pengaturan Sistem</span>
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
            </div>

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
                    <button id="sidebarToggle" class="btn btn-sm d-md-none" aria-label="Toggle sidebar" style="margin-right:0.5rem;">
                        <i class="bi bi-list" style="font-size:1.25rem;color:var(--sidebar-dark);"></i>
                    </button>
                    <h5 class="navbar-title">Sistem Ticketing Laboran</h5>
                    <div class="navbar-user">
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <p class="user-name">{{ auth()->user()->name }}</p>
                                <p class="user-role">{{ auth()->user()?->role?->nama_role ?? 'Tanpa Role' }}</p>
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
    <script>
        (function(){
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !toggle || !backdrop) return;

            function toggleSidebar() {
                const show = sidebar.classList.toggle('show');
                if (show) {
                    backdrop.classList.add('show');
                    document.body.style.overflow = 'hidden';
                } else {
                    backdrop.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', function(e){
                e.stopPropagation();
                toggleSidebar();
            });

            backdrop.addEventListener('click', closeSidebar);

            // Close when clicking outside on small screens
            document.addEventListener('click', function(e){
                if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                    if (!sidebar.contains(e.target) && e.target !== toggle && e.target !== backdrop) {
                        closeSidebar();
                    }
                }
            });

            // Close on Escape
            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    closeSidebar();
                }
            });
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('extra_js')
</body>
</html>
