<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Citra Gran Management</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-container {
            width: 100%;
            max-width: 480px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.4);
        }

        .register-title {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            color: #64748b;
        }

        .register-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 10px;
            color: #fff;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.5);
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
        }

        .register-footer a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .register-footer a:hover {
            color: #a5b4fc;
        }

        .form-hint {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        @media (max-width: 500px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="register-logo">
                <i class="bi bi-building"></i>
            </div>
            <h1 class="register-title">Daftar Akun</h1>
            <p class="register-subtitle">Bergabung dengan Citra Gran Management</p>
        </div>

        <div class="register-card">
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

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label" for="phone">No. WhatsApp <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                        <p class="form-hint">Digunakan untuk login</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email (Opsional)</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                    <p class="form-hint">Password minimal 8 karakter</p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i>
                    Daftar
                </button>
            </form>

            <div class="register-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>

        <p style="text-align: center; color: #475569; margin-top: 2rem; font-size: 0.875rem;">
            <a href="{{ route('home') }}" style="color: #94a3b8; text-decoration: none;">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </p>
    </div>
</body>
</html>
