<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Citra Gran Management - Sistem Manajemen Perumahan Modern">
    <title>Citra Gran Management - Sistem Manajemen Perumahan</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            z-index: 1000;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .logo-text {
            font-weight: 700;
            font-size: 1.25rem;
            background: linear-gradient(135deg, #818cf8, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-link {
            padding: 0.625rem 1.25rem;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #fff;
            background: rgba(99, 102, 241, 0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary {
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(99, 102, 241, 0.2);
        }

        /* Hero Section */
        .hero {
            padding-top: 120px;
            padding-bottom: 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 50px;
            color: #818cf8;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero h1 span {
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: #94a3b8;
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Features */
        .features {
            padding: 80px 2rem;
            background: rgba(15, 23, 42, 0.5);
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
        }

        .section-title p {
            color: #94a3b8;
            font-size: 1.125rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(99, 102, 241, 0.1);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(14, 165, 233, 0.2));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: #818cf8;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Announcements */
        .announcements {
            padding: 80px 2rem;
        }

        .announcements-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .announcement-card {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(99, 102, 241, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .announcement-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
        }

        .announcement-date {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .announcement-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .announcement-excerpt {
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            padding: 40px 2rem;
            background: rgba(15, 23, 42, 0.8);
            border-top: 1px solid rgba(99, 102, 241, 0.1);
            text-align: center;
        }

        .footer p {
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding-top: 100px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-icon">
                <i class="bi bi-building"></i>
            </div>
            <span class="logo-text">Citra Gran</span>
        </a>

        <div class="nav-links">
            @auth
                @if(auth()->user()->isStaff())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('resident.dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i>
                    Daftar
                </a>
            @endauth
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-stars"></i>
                Sistem Manajemen Modern
            </div>
            <h1>
                Kelola Perumahan Anda dengan <span>Citra Gran</span> Management
            </h1>
            <p>
                Platform modern untuk manajemen perumahan yang efisien. Kelola tagihan IPL, laporan warga, karyawan, dan keuangan dalam satu sistem terintegrasi.
            </p>
            <div class="hero-buttons">
                @auth
                    @if(auth()->user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-speedometer2"></i>
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('resident.dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-speedometer2"></i>
                            Lihat Tagihan Saya
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Masuk Sekarang
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-person-plus"></i>
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-container">
            <div class="section-title">
                <h2>Fitur Unggulan</h2>
                <p>Semua yang Anda butuhkan untuk mengelola perumahan dengan efisien</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <h3>Tagihan IPL</h3>
                    <p>Kelola tagihan IPL bulanan dengan rincian lengkap: Air PAM, Kebersihan, Sampah, dan Security. Kirim notifikasi otomatis via WhatsApp & Email.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-chat-square-text"></i>
                    </div>
                    <h3>Laporan Warga</h3>
                    <p>Terima dan kelola laporan/komplain dari warga dengan sistem tiket. Pantau status dari diterima hingga selesai.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3>Manajemen Karyawan</h3>
                    <p>Database lengkap karyawan: Satpam, Kebersihan, Sampah, dan Teknik. Termasuk sistem penggajian harian, mingguan, dan bulanan.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h3>Penggajian</h3>
                    <p>Proses penggajian otomatis dengan dukungan potongan hutang karyawan. Laporan gaji lengkap per periode.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h3>Laporan Keuangan</h3>
                    <p>Laporan pendapatan dari IPL, pengeluaran operasional, dan pertanggungjawaban dana yang transparan untuk warga.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h3>Akses Warga</h3>
                    <p>Portal khusus warga untuk melihat tagihan, membuat laporan, dan mengakses laporan keuangan bulanan perumahan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Announcements -->
    @if($announcements->count() > 0)
    <section class="announcements">
        <div class="announcements-container">
            <div class="section-title">
                <h2>Pengumuman Terbaru</h2>
                <p>Informasi terbaru dari Management Perumahan</p>
            </div>

            @foreach($announcements as $announcement)
                <div class="announcement-card">
                    <div class="announcement-date">
                        {{ $announcement->published_at?->format('d M Y') ?? $announcement->created_at->format('d M Y') }}
                    </div>
                    <h3 class="announcement-title">{{ $announcement->title }}</h3>
                    <p class="announcement-excerpt">{{ Str::limit(strip_tags($announcement->content), 200) }}</p>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} <a href="https://ghiya.my.id" target="_blank" style="color: #818cf8; text-decoration: none;">Gerrant Hiya</a> (ghiya.my.id)</p>
    </footer>
</body>
</html>
