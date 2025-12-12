@extends('layouts.admin')

@section('title', 'Laporan Warga')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Laporan/Komplain</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.reports.index') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <input type="text" name="search" class="form-control" style="flex: 1; min-width: 200px;" placeholder="Cari tiket, subjek..." value="{{ request('search') }}">
                <select name="status" class="form-control" style="min-width: 130px;">
                    <option value="">Semua Status</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Diterima</option>
                    <option value="analyzing" {{ request('status') == 'analyzing' ? 'selected' : '' }}>Dianalisa</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <select name="type" class="form-control" style="min-width: 130px;">
                    <option value="">Semua Jenis</option>
                    <option value="billing" {{ request('type') == 'billing' ? 'selected' : '' }}>Billing</option>
                    <option value="environment" {{ request('type') == 'environment' ? 'selected' : '' }}>Lingkungan</option>
                    <option value="dispute" {{ request('type') == 'dispute' ? 'selected' : '' }}>Perselisihan</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tiket</th>
                        <th>Warga</th>
                        <th>Subjek</th>
                        <th>Jenis</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td><a href="{{ route('admin.reports.show', $report) }}" style="color: var(--primary);">{{ $report->ticket_number }}</a></td>
                            <td>{{ $report->resident->name ?? '-' }}</td>
                            <td>{{ Str::limit($report->subject, 30) }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($report->type) }}</span></td>
                            <td>
                                @switch($report->priority)
                                    @case('urgent')
                                        <span class="badge badge-danger">Mendesak</span>
                                        @break
                                    @case('high')
                                        <span class="badge badge-warning">Tinggi</span>
                                        @break
                                    @default
                                        <span class="badge badge-info">{{ ucfirst($report->priority) }}</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($report->status)
                                    @case('received')
                                        <span class="badge badge-info">Diterima</span>
                                        @break
                                    @case('analyzing')
                                        <span class="badge badge-warning">Dianalisa</span>
                                        @break
                                    @case('processing')
                                        <span class="badge badge-primary">Diproses</span>
                                        @break
                                    @case('completed')
                                        <span class="badge badge-success">Selesai</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $report->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.reports.show', $report) }}" class="action-btn view"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem;">Belum ada laporan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="pagination">{{ $reports->links() }}</div>
        @endif
    </div>
</div>
@endsection
