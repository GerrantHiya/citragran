@extends('layouts.resident')

@section('title', 'Buat Laporan')

@section('content')
<div class="page-header">
    <a href="{{ route('resident.reports.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h1 class="page-title">Buat Laporan Baru</h1>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body">
        <form action="{{ route('resident.reports.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Jenis Laporan *</label>
                <select name="type" class="form-control" required>
                    <option value="">Pilih Jenis</option>
                    <option value="billing">Billing / Tagihan</option>
                    <option value="environment">Lingkungan</option>
                    <option value="dispute">Perselisihan</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Prioritas *</label>
                <select name="priority" class="form-control" required>
                    <option value="low">Rendah</option>
                    <option value="medium" selected>Sedang</option>
                    <option value="high">Tinggi</option>
                    <option value="urgent">Mendesak</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Subjek *</label>
                <input type="text" name="subject" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi *</label>
                <textarea name="description" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send"></i> Kirim Laporan
            </button>
        </form>
    </div>
</div>
@endsection
