@extends('layouts.admin')

@section('title', 'Data Warga')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Warga</h3>
        <a href="{{ route('admin.residents.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Warga
        </a>
    </div>
    <div class="card-body">
        @if(isset($unlinkedUsers) && $unlinkedUsers->count() > 0)
            <div class="alert alert-warning" style="margin-bottom: 1.5rem;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>{{ $unlinkedUsers->count() }} akun warga belum terhubung dengan data warga.</strong>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem;">
                        Akun yang mendaftar sendiri perlu dihubungkan dengan data warga. Klik "Lihat" pada warga terkait untuk menghubungkan akun.
                    </p>
                    <div style="margin-top: 0.5rem; font-size: 0.875rem;">
                        @foreach($unlinkedUsers->take(3) as $user)
                            <span class="badge badge-warning" style="margin-right: 0.25rem;">{{ $user->name }} ({{ $user->email }})</span>
                        @endforeach
                        @if($unlinkedUsers->count() > 3)
                            <span style="color: var(--gray-600);">dan {{ $unlinkedUsers->count() - 3 }} lainnya...</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter -->
        <form action="{{ route('admin.residents.index') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, blok, email..." value="{{ request('search') }}">
                </div>
                <div style="min-width: 150px;">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">
                    <i class="bi bi-search"></i>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i>
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Blok</th>
                        <th>Nama</th>
                        <th>Luas Tanah</th>
                        <th>IPL/Bulan</th>
                        <th>Status</th>
                        <th>Tunggakan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $resident)
                        <tr>
                            <td>
                                <strong style="color: var(--primary);">{{ $resident->block_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $resident->name }}</strong>
                                    @if($resident->user)
                                        <span class="badge badge-success" style="font-size: 0.65rem;">Has Account</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($resident->land_area)
                                    {{ number_format($resident->land_area, 0) }} m²
                                @else
                                    <span style="color: var(--gray-500);">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="amount" style="color: var(--success);">Rp {{ number_format($resident->ipl_amount ?? 0, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($resident->status === 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                @php $outstanding = $resident->total_outstanding; @endphp
                                @if($outstanding > 0)
                                    <span class="amount negative">Rp {{ number_format($outstanding, 0, ',', '.') }}</span>
                                @else
                                    <span class="badge badge-success">Lunas</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.residents.show', $resident) }}" class="action-btn view" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.residents.edit', $resident) }}" class="action-btn edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data warga ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="empty-state-title">Belum ada data warga</div>
                                    <div class="empty-state-text">Klik tombol "Tambah Warga" untuk menambahkan data warga baru.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($residents->hasPages())
            <div class="pagination">
                {{ $residents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
