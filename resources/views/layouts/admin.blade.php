<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Citra Gran Management</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
            --sidebar-width: 280px;
            --header-height: 70px;
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

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(99, 102, 241, 0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(14, 165, 233, 0.1));
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .sidebar-logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .sidebar-logo-text {
            font-weight: 700;
            font-size: 1.25rem;
            background: linear-gradient(135deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gray-500);
            padding: 1rem 0.75rem 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            color: var(--gray-400);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 0.25rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--white);
        }

        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .header {
            height: var(--header-height);
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--gray-400);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--white), var(--gray-300));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-notifications {
            position: relative;
            color: var(--gray-400);
            cursor: pointer;
            font-size: 1.25rem;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .header-user:hover {
            background: rgba(99, 102, 241, 0.2);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--white);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* Content Area */
        .content {
            padding: 2rem;
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
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
            border: 1px solid rgba(99, 102, 241, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1));
            color: var(--primary-light);
        }

        .stat-icon.success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
            color: var(--success);
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1));
            color: var(--warning);
        }

        .stat-icon.danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
            color: var(--danger);
        }

        .stat-icon.secondary {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.2), rgba(14, 165, 233, 0.1));
            color: var(--secondary);
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-400);
            margin-top: 0.25rem;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
        }

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

        .table tr:hover td {
            background: rgba(99, 102, 241, 0.05);
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
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-light);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(99, 102, 241, 0.2);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: white;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
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
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .form-control::placeholder {
            color: var(--gray-500);
        }

        select.form-control {
            cursor: pointer;
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

        .alert-warning {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: var(--warning);
        }

        .alert-info {
            background: rgba(14, 165, 233, 0.15);
            border: 1px solid rgba(14, 165, 233, 0.3);
            color: var(--secondary);
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
            margin-top: 1.5rem;
        }

        .pagination a, .pagination span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination a {
            background: rgba(99, 102, 241, 0.1);
            color: var(--gray-400);
        }

        .pagination a:hover {
            background: rgba(99, 102, 241, 0.2);
            color: var(--white);
        }

        .pagination .active span {
            background: var(--primary);
            color: white;
        }

        .pagination .disabled span {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Grid Layout */
        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 200px;
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

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--gray-300);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--white);
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 1rem;
            }

            .header {
                padding: 0 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header-user .user-info {
                display: none;
            }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: rgba(30, 41, 59, 0.98);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.125rem;
            color: var(--white);
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--gray-400);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: var(--white);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(99, 102, 241, 0.1);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--gray-500);
            margin-bottom: 1rem;
        }

        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-300);
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            color: var(--gray-500);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .action-btn.view {
            background: rgba(14, 165, 233, 0.1);
            color: var(--secondary);
        }

        .action-btn.edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        /* Amount formatting */
        .amount {
            font-weight: 600;
            font-family: 'Inter', monospace;
        }

        .amount.positive {
            color: var(--success);
        }

        .amount.negative {
            color: var(--danger);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="bi bi-building"></i>
                </div>
                <span class="sidebar-logo-text">Citra Gran</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Warga & Tagihan</div>
                <a href="{{ route('admin.residents.index') }}" class="nav-link {{ request()->routeIs('admin.residents.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Warga</span>
                </a>
                <a href="{{ route('admin.ipl-bills.index') }}" class="nav-link {{ request()->routeIs('admin.ipl-bills.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Tagihan IPL</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-square-text-fill"></i>
                    <span>Laporan Warga</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Kepegawaian</div>
                <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Data Karyawan</span>
                </a>
                <a href="{{ route('admin.payrolls.index') }}" class="nav-link {{ request()->routeIs('admin.payrolls.*') && !request()->routeIs('admin.payrolls.report') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i>
                    <span>Penggajian</span>
                </a>
                <a href="{{ route('admin.employee-debts.index') }}" class="nav-link {{ request()->routeIs('admin.employee-debts.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card-2-front-fill"></i>
                    <span>Hutang Karyawan</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Keuangan</div>
                <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ request()->routeIs('admin.expenses.*') && !request()->routeIs('admin.expenses.report') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i>
                    <span>Pengeluaran</span>
                </a>
                <a href="{{ route('admin.financial-reports.index') }}" class="nav-link {{ request()->routeIs('admin.financial-reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Laporan</div>
                <a href="{{ route('admin.financial-reports.income') }}" class="nav-link {{ request()->routeIs('admin.financial-reports.income') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Laporan Pendapatan</span>
                </a>
                <a href="{{ route('admin.expenses.report') }}" class="nav-link {{ request()->routeIs('admin.expenses.report') ? 'active' : '' }}">
                    <i class="bi bi-graph-down-arrow"></i>
                    <span>Laporan Pengeluaran</span>
                </a>
                <a href="{{ route('admin.payrolls.report') }}" class="nav-link {{ request()->routeIs('admin.payrolls.report') ? 'active' : '' }}">
                    <i class="bi bi-file-person-fill"></i>
                    <span>Laporan Gaji</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">@yield('title', 'Dashboard')</h1>
            </div>

            <div class="header-right">
                <div class="dropdown">
                    <div class="header-user">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <span class="user-role">{{ auth()->user()->role_name }}</span>
                        </div>
                        <i class="bi bi-chevron-down" style="color: var(--gray-500);"></i>
                    </div>
                    <div class="dropdown-menu">
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="bi bi-person"></i>
                            <span>Profil</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width: 100%; background: none; border: none; cursor: pointer;">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
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

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <ul style="margin: 0; padding-left: 1rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }
    </script>
    @stack('scripts')
</body>
</html>
