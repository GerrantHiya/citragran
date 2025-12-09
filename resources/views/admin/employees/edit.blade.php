@extends('layouts.admin')

@section('title', 'Edit Karyawan')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Edit Karyawan - {{ $employee->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe Karyawan *</label>
                    <select name="type" class="form-control" required>
                        <option value="security" {{ $employee->type == 'security' ? 'selected' : '' }}>Satpam</option>
                        <option value="cleaning" {{ $employee->type == 'cleaning' ? 'selected' : '' }}>Kebersihan</option>
                        <option value="garbage" {{ $employee->type == 'garbage' ? 'selected' : '' }}>Sampah</option>
                        <option value="technician" {{ $employee->type == 'technician' ? 'selected' : '' }}>Teknik</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Gaji Pokok *</label>
                    <input type="number" name="base_salary" class="form-control" value="{{ old('base_salary', $employee->base_salary) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Periode Gaji *</label>
                    <select name="salary_type" class="form-control" required>
                        <option value="daily" {{ $employee->salary_type == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ $employee->salary_type == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $employee->salary_type == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
