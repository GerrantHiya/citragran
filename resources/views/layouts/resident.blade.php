<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Portal Warga</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f1f5f9;
            --white: #ffffff;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            color: var(--gray-100);
        }

        /* Header */
        .header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .logo-text {
            font-weight: 700;
            font-size: 1.125rem;
            background: linear-gradient(135deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link {
            padding: 0.5rem 1rem;
            color: var(--gray-400);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--white);
            background: rgba(99, 102, 241, 0.1);
        }

        .nav-link.active {
            color: var(--primary-light);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 50px;
            cursor: pointer;
            position: relative;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 0.875rem;
        }

        .user-name {
            font-weight: 500;
            color: var(--white);
            font-size: 0.875rem;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            min-width: 180px;
            background: rgba(30, 41, 59, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 12px;
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .user-menu:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            color: var(--gray-300);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--white);
        }

        /* Main Content */
        .main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--gray-500);
        }

        /* Cards */
        .card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(99, 102, 241, 0.1);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: rgba(99, 102, 241, 0.3);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--white);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
            border: 1px solid rgba(99, 102, 241, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }

        .stat-icon.success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning);
        }

        .stat-icon.info {
            background: rgba(14, 165, 233, 0.2);
            color: var(--secondary);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-400);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-secondary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-light);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-300);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 10px;
            color: var(--white);
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-400);
            background: rgba(15, 23, 42, 0.5);
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.05);
            color: var(--gray-300);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 50px;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }

        .badge-info {
            background: rgba(14, 165, 233, 0.2);
            color: var(--secondary);
        }

        .badge-primary {
            background: rgba(99, 102, 241, 0.2);
            color: var(--primary-light);
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--danger);
        }

        .alert-info {
            background: rgba(14, 165, 233, 0.15);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: var(--secondary);
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        /* Amount */
        .amount {
            font-weight: 600;
            font-family: monospace;
        }

        .amount.positive {
            color: var(--success);
        }

        .amount.negative {
            color: var(--danger);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }

            .nav {
                display: none;
            }

            .main {
                padding: 1rem;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }

            .user-name {
                display: none;
            }
        }

        /* Mobile Nav */
        .mobile-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(99, 102, 241, 0.1);
            padding: 0.75rem;
            z-index: 1000;
        }

        .mobile-nav-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
        }

        .mobile-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            padding: 0.5rem;
            color: var(--gray-400);
            text-decoration: none;
            font-size: 0.625rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .mobile-nav-link i {
            font-size: 1.25rem;
        }

        .mobile-nav-link.active, .mobile-nav-link:hover {
            color: var(--primary-light);
            background: rgba(99, 102, 241, 0.1);
        }

        @media (max-width: 768px) {
            .mobile-nav {
                display: block;
            }

            .main {
                padding-bottom: 80px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="{{ route('resident.dashboard') }}" class="logo">
                <div class="logo-icon">
                    <i class="bi bi-building"></i>
                </div>
                <span class="logo-text">Citra Gran</span>
            </a>

            <nav class="nav">
                <a href="{{ route('resident.dashboard') }}" class="nav-link {{ request()->routeIs('resident.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('resident.bills.index') }}" class="nav-link {{ request()->routeIs('resident.bills.*') ? 'active' : '' }}">
                    Tagihan
                </a>
                <a href="{{ route('resident.reports.index') }}" class="nav-link {{ request()->routeIs('resident.reports.*') ? 'active' : '' }}">
                    Laporan
                </a>
                <a href="{{ route('resident.financial-reports.index') }}" class="nav-link {{ request()->routeIs('resident.financial-reports.*') ? 'active' : '' }}">
                    Keuangan
                </a>
                <a href="{{ route('resident.announcements') }}" class="nav-link {{ request()->routeIs('resident.announcements') ? 'active' : '' }}">
                    Pengumuman
                </a>
            </nav>

            <div class="user-menu">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="user-name">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down" style="color: var(--gray-500); font-size: 0.75rem;"></i>
                
                <div class="dropdown-menu">
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <i class="bi bi-person"></i>
                        <span>Profil</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @yield('content')

        <!-- Footer -->
        <footer style="text-align: center; padding: 2rem 1rem 1rem; margin-top: 2rem; border-top: 1px solid rgba(99, 102, 241, 0.1);">
            <p style="color: var(--gray-500); font-size: 0.875rem; margin: 0;">
                {{ date('Y') }} &copy; <a href="https://ghiya.my.id" target="_blank" style="color: var(--primary-light); text-decoration: none;">Gerrant Hiya</a> | All rights reserved.
            </p>
        </footer>
    </main>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav">
        <div class="mobile-nav-grid">
            <a href="{{ route('resident.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('resident.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('resident.bills.index') }}" class="mobile-nav-link {{ request()->routeIs('resident.bills.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Tagihan</span>
            </a>
            <a href="{{ route('resident.reports.create') }}" class="mobile-nav-link">
                <i class="bi bi-plus-circle-fill" style="font-size: 1.75rem; color: var(--primary-light);"></i>
                <span>Lapor</span>
            </a>
            <a href="{{ route('resident.reports.index') }}" class="mobile-nav-link {{ request()->routeIs('resident.reports.*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text"></i>
                <span>Laporan</span>
            </a>
            <a href="{{ route('profile') }}" class="mobile-nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>Akun</span>
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
