@extends('layouts.resident')

@section('title', 'Detail Laporan')

@section('content')
<div class="page-header">
    <a href="{{ route('resident.reports.index') }}" style="color: var(--gray-400); text-decoration: none;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h1 class="page-title">{{ $report->ticket_number }}</h1>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Laporan</h3>
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
        </div>
        <div class="card-body">
            <div style="margin-bottom: 1rem;">
                <label style="color: var(--gray-500); font-size: 0.75rem;">SUBJEK</label>
                <p style="color: var(--white); font-weight: 600;">{{ $report->subject }}</p>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="color: var(--gray-500); font-size: 0.75rem;">JENIS</label>
                <p><span class="badge badge-info">{{ ucfirst($report->type) }}</span></p>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="color: var(--gray-500); font-size: 0.75rem;">DESKRIPSI</label>
                <p style="color: var(--gray-300); line-height: 1.7;">{{ $report->description }}</p>
            </div>
            <div>
                <label style="color: var(--gray-500); font-size: 0.75rem;">TANGGAL DIBUAT</label>
                <p style="color: var(--gray-300);">{{ $report->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Komentar</h3>
        </div>
        <div class="card-body">
            @forelse($report->publicComments as $comment)
                <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <strong style="color: var(--white);">{{ $comment->user->name }}</strong>
                        <span style="color: var(--gray-500); font-size: 0.75rem;">{{ $comment->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <p style="color: var(--gray-300);">{{ $comment->comment }}</p>
                </div>
            @empty
                <p style="text-align: center; color: var(--gray-500);">Belum ada komentar</p>
            @endforelse

            @if($report->status !== 'completed' && $report->status !== 'rejected')
                <form action="{{ route('resident.reports.add-comment', $report) }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <div class="form-group">
                        <textarea name="comment" class="form-control" rows="3" placeholder="Tulis komentar..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
