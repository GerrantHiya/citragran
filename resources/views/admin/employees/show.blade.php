@extends('layouts.admin')

@section('title', 'Detail Karyawan')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Profil Karyawan</h3>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-secondary btn-sm"><i class="bi bi-pencil"></i> Edit</a>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                </div>
                <div>
                    <h2 style="color: var(--white); margin: 0;">{{ $employee->name }}</h2>
                    <p style="color: var(--primary-light);">{{ $employee->employee_code }}</p>
                    @if($employee->status === 'active')
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Tidak Aktif</span>
                    @endif
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="color: var(--gray-500); font-size: 0.75rem;">TIPE</label>
                    <p style="color: var(--white);">{{ $employee->type_name }}</p>
                </div>
                <div>
                    <label style="color: var(--gray-500); font-size: 0.75rem;">GAJI POKOK</label>
                    <p style="color: var(--success);">Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label style="color: var(--gray-500); font-size: 0.75rem;">TELEPON</label>
                    <p style="color: var(--white);">{{ $employee->phone ?? '-' }}</p>
                </div>
                <div>
                    <label style="color: var(--gray-500); font-size: 0.75rem;">BERGABUNG</label>
                    <p style="color: var(--white);">{{ $employee->join_date?->format('d M Y') ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Statistik</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; gap: 1rem;">
                <div style="background: rgba(16, 185, 129, 0.1); padding: 1rem; border-radius: 12px;">
                    <div style="color: var(--success); font-size: 1.5rem; font-weight: 700;">Rp {{ number_format($employee->payrolls->where('status', 'paid')->sum('total_amount'), 0, ',', '.') }}</div>
                    <div style="color: var(--gray-400);">Total Gaji Dibayar</div>
                </div>
                <div style="background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 12px;">
                    <div style="color: var(--danger); font-size: 1.5rem; font-weight: 700;">Rp {{ number_format($employee->total_outstanding_debt, 0, ',', '.') }}</div>
                    <div style="color: var(--gray-400);">Sisa Hutang</div>
                </div>
            </div>
        </div>
    </div>
</div>

<a href="{{ route('admin.employees.index') }}" class="btn btn-secondary" style="margin-top: 1.5rem;">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection
