@extends('layouts.resident')

@section('title', 'Laporan Saya')

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title">Laporan Saya</h1>
        <p class="page-subtitle">Daftar laporan dan komplain Anda</p>
    </div>
    <a href="{{ route('resident.reports.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Buat Laporan
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Tiket</th>
                    <th>Subjek</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>
                            <a href="{{ route('resident.reports.show', $report) }}" style="color: var(--primary-light);">
                                {{ $report->ticket_number }}
                            </a>
                        </td>
                        <td>{{ Str::limit($report->subject, 40) }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($report->type) }}</span></td>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">
                            Belum ada laporan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($reports->hasPages())
    <div style="margin-top: 1rem;">{{ $reports->links() }}</div>
@endif
@endsection
