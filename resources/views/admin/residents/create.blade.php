@extends('layouts.admin')

@section('title', 'Tambah Warga')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Warga</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.residents.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="block_number">Nomor Blok <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="block_number" name="block_number" class="form-control" placeholder="Contoh: A1, B2" value="{{ old('block_number') }}" required>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label" for="land_area">Luas Tanah (m²) <span style="color: var(--danger);">*</span></label>
                    <input type="number" id="land_area" name="land_area" class="form-control" step="0.01" min="0" placeholder="Contoh: 72, 120, 200" value="{{ old('land_area') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ipl_amount">Tarif IPL per Bulan (Rp) <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="ipl_amount" name="ipl_amount" class="form-control money-input" placeholder="Contoh: 200,000" value="{{ old('ipl_amount', 0) }}" required>
                    <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.25rem;">
                        <i class="bi bi-info-circle"></i> Tarif IPL bulanan untuk warga ini
                    </p>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label" for="phone">No. Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="whatsapp">No. WhatsApp</label>
                    <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}">
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="move_in_date">Tanggal Masuk</label>
                    <input type="date" id="move_in_date" name="move_in_date" class="form-control" value="{{ old('move_in_date') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Lengkap</label>
                <textarea id="address" name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
            </div>

            <!-- Create Account Section -->
            <div style="background: rgba(99, 102, 241, 0.1); border-radius: 12px; padding: 1.5rem; margin-top: 1.5rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 1rem;">
                    <input type="checkbox" id="create_account" name="create_account" value="1" style="margin-top: 0.25rem;" {{ old('create_account') ? 'checked' : '' }}>
                    <div>
                        <label for="create_account" style="font-weight: 600; color: var(--text-primary); cursor: pointer;">Buat Akun Login</label>
                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">
                            Buat akun agar warga dapat login untuk melihat tagihan dan membuat laporan
                        </p>
                    </div>
                </div>
                
                <div id="accountFields" style="display: none;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                    </div>
                    <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.5rem;">
                        <i class="bi bi-info-circle"></i> Email warga akan digunakan sebagai username login
                    </p>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Simpan
                </button>
                <a href="{{ route('admin.residents.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const createAccountCheckbox = document.getElementById('create_account');
    const accountFields = document.getElementById('accountFields');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    createAccountCheckbox.addEventListener('change', function() {
        accountFields.style.display = this.checked ? 'block' : 'none';
        passwordInput.required = this.checked;
    });

    // Initial state
    if (createAccountCheckbox.checked) {
        accountFields.style.display = 'block';
        passwordInput.required = true;
    }
</script>
@endpush
@endsection
