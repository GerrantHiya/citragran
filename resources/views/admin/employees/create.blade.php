@extends('layouts.admin')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Karyawan</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe Karyawan *</label>
                    <select name="type" class="form-control" required>
                        <option value="security">Satpam</option>
                        <option value="cleaning">Kebersihan</option>
                        <option value="garbage">Sampah</option>
                        <option value="technician">Teknik</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Gaji Pokok *</label>
                    <input type="number" name="base_salary" class="form-control" value="{{ old('base_salary', 0) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Periode Gaji *</label>
                    <select name="salary_type" class="form-control" required>
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                        <option value="monthly" selected>Bulanan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" name="join_date" class="form-control" value="{{ old('join_date', date('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
