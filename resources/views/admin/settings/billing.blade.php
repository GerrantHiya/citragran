@extends('layouts.admin')

@section('title', 'Pengaturan Tagihan')

@section('content')
<div class="grid" style="gap: 1.5rem; max-width: 800px;">
    <!-- Iuran RT Setting -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-people"></i> Pengaturan Iuran RT</h3>
        </div>
        <div class="card-body">
            <p style="color: var(--gray-400); margin-bottom: 1.5rem;">
                Iuran RT akan dikenakan sama untuk semua warga aktif saat generate tagihan bulanan.
            </p>

            <form action="{{ route('admin.settings.rt-fee') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="name">Nama Iuran <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $rtFee->name ?? 'Iuran RT Bulanan') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="amount">Nominal (Rp) <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="amount" name="amount" class="form-control money-input" value="{{ old('amount', $rtFee->amount ?? 0) }}" required>
                    <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.25rem;">
                        <i class="bi bi-info-circle"></i> Nominal yang akan ditagihkan ke setiap warga per bulan
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Keterangan</label>
                    <textarea id="description" name="description" class="form-control" rows="2">{{ old('description', $rtFee->description ?? '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>

    <!-- Info -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-info-circle"></i> Informasi Tagihan</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; gap: 1rem;">
                <div style="background: rgba(99, 102, 241, 0.1); padding: 1rem; border-radius: 8px;">
                    <strong style="color: var(--primary-light);">IPL (Iuran Pengelolaan Lingkungan)</strong>
                    <p style="margin: 0.5rem 0 0; color: var(--gray-400); font-size: 0.875rem;">
                        Tarif IPL diatur per warga berdasarkan luas tanah. Edit di halaman Data Warga.
                    </p>
                </div>
                <div style="background: rgba(16, 185, 129, 0.1); padding: 1rem; border-radius: 8px;">
                    <strong style="color: var(--success);">Iuran RT</strong>
                    <p style="margin: 0.5rem 0 0; color: var(--gray-400); font-size: 0.875rem;">
                        Tarif iuran RT sama untuk semua warga. Diatur di halaman ini.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
