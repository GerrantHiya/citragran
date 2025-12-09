@extends('layouts.admin')

@section('title', 'Data Karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Karyawan</h3>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Karyawan
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.employees.index') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <input type="text" name="search" class="form-control" style="flex: 1; min-width: 200px;" placeholder="Cari nama, kode..." value="{{ request('search') }}">
                <select name="type" class="form-control" style="min-width: 150px;">
                    <option value="">Semua Tipe</option>
                    <option value="security" {{ request('type') == 'security' ? 'selected' : '' }}>Satpam</option>
                    <option value="cleaning" {{ request('type') == 'cleaning' ? 'selected' : '' }}>Kebersihan</option>
                    <option value="garbage" {{ request('type') == 'garbage' ? 'selected' : '' }}>Sampah</option>
                    <option value="technician" {{ request('type') == 'technician' ? 'selected' : '' }}>Teknik</option>
                </select>
                <select name="status" class="form-control" style="min-width: 130px;">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Gaji</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td><strong style="color: var(--primary-light);">{{ $employee->employee_code }}</strong></td>
                            <td>{{ $employee->name }}</td>
                            <td><span class="badge badge-info">{{ $employee->type_name }}</span></td>
                            <td>Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</td>
                            <td>
                                @if($employee->status === 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.employees.show', $employee) }}" class="action-btn view"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="action-btn edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:2rem;">Belum ada karyawan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($employees->hasPages())
            <div class="pagination">{{ $employees->links() }}</div>
        @endif
    </div>
</div>
@endsection
