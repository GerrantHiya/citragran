@extends('layouts.admin')

@section('title', 'Edit Warga')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Edit Data Warga - {{ $resident->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.residents.update', $resident) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $resident->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="block_number">Nomor Blok <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="block_number" name="block_number" class="form-control" value="{{ old('block_number', $resident->block_number) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="land_area">Luas Tanah (m²) <span style="color: var(--danger);">*</span></label>
                <input type="number" id="land_area" name="land_area" class="form-control" step="0.01" min="0" placeholder="Contoh: 72, 120, 200" value="{{ old('land_area', $resident->land_area) }}" required>
                @if($resident->ipl_rate)
                    <p style="font-size: 0.75rem; color: var(--success); margin-top: 0.25rem;">
                        <i class="bi bi-check-circle"></i> Kategori: {{ $resident->ipl_rate->name }} - Rp {{ number_format($resident->ipl_rate->ipl_amount, 0, ',', '.') }}/bulan
                    </p>
                @else
                    <p style="font-size: 0.75rem; color: var(--warning); margin-top: 0.25rem;">
                        <i class="bi bi-exclamation-triangle"></i> Belum ada tarif IPL yang sesuai
                    </p>
                @endif
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label" for="phone">No. Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $resident->phone) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="whatsapp">No. WhatsApp</label>
                    <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="{{ old('whatsapp', $resident->whatsapp) }}">
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $resident->email) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="move_in_date">Tanggal Masuk</label>
                    <input type="date" id="move_in_date" name="move_in_date" class="form-control" value="{{ old('move_in_date', $resident->move_in_date?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label" for="status">Status <span style="color: var(--danger);">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active" {{ old('status', $resident->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $resident->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Lengkap</label>
                <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $resident->address) }}</textarea>
            </div>

            @if($resident->user)
                <div style="background: rgba(16, 185, 129, 0.1); border-radius: 12px; padding: 1rem; margin-top: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--success);">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Warga ini memiliki akun login ({{ $resident->user->email }})</span>
                    </div>
                </div>
            @endif

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Update
                </button>
                <a href="{{ route('admin.residents.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
