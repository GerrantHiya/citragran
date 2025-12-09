@extends('layouts.admin')

@section('title', 'Detail Laporan')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $report->ticket_number }}</h3>
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
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="color: var(--gray-500); font-size: 0.75rem;">WARGA</label>
                    <p style="color: var(--white);">{{ $report->resident->name ?? '-' }} ({{ $report->resident->block_number ?? '-' }})</p>
                </div>
                <div>
                    <label style="color: var(--gray-500); font-size: 0.75rem;">JENIS</label>
                    <p><span class="badge badge-info">{{ ucfirst($report->type) }}</span></p>
                </div>
                <div>
                    <label style="color: var(--gray-500); font-size: 0.75rem;">PRIORITAS</label>
                    <p><span class="badge badge-{{ $report->priority == 'urgent' ? 'danger' : ($report->priority == 'high' ? 'warning' : 'info') }}">{{ ucfirst($report->priority) }}</span></p>
                </div>
                <div>
                    <label style="color: var(--gray-500); font-size: 0.75rem;">TANGGAL</label>
                    <p style="color: var(--gray-300);">{{ $report->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="color: var(--gray-500); font-size: 0.75rem;">SUBJEK</label>
                <p style="color: var(--white); font-weight: 600;">{{ $report->subject }}</p>
            </div>
            <div>
                <label style="color: var(--gray-500); font-size: 0.75rem;">DESKRIPSI</label>
                <p style="color: var(--gray-300); line-height: 1.7;">{{ $report->description }}</p>
            </div>
        </div>
    </div>

    <div>
        <!-- Update Status -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Update Status</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.update-status', $report) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-control" style="margin-bottom: 1rem;">
                        <option value="received" {{ $report->status == 'received' ? 'selected' : '' }}>Diterima</option>
                        <option value="analyzing" {{ $report->status == 'analyzing' ? 'selected' : '' }}>Dianalisa</option>
                        <option value="processing" {{ $report->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ $report->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Add Comment -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Komentar</h3>
            </div>
            <div class="card-body">
                @foreach($report->comments as $comment)
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                        <div style="display: flex; justify-content: space-between;">
                            <strong style="color: var(--white);">{{ $comment->user->name }}</strong>
                            <span style="font-size: 0.75rem; color: var(--gray-500);">{{ $comment->created_at->format('d M H:i') }}</span>
                        </div>
                        <p style="color: var(--gray-300);">{{ $comment->comment }}</p>
                        @if($comment->is_internal)
                            <span class="badge badge-warning" style="font-size: 0.6rem;">Internal</span>
                        @endif
                    </div>
                @endforeach

                <form action="{{ route('admin.reports.add-comment', $report) }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <textarea name="comment" class="form-control" rows="2" placeholder="Tulis komentar..." required style="margin-bottom: 0.5rem;"></textarea>
                    <label style="color: var(--gray-400); font-size: 0.875rem;">
                        <input type="checkbox" name="is_internal" value="1"> Internal (tidak terlihat warga)
                    </label>
                    <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 0.5rem;">Kirim</button>
                </form>
            </div>
        </div>
    </div>
</div>

<a href="{{ route('admin.reports.index') }}" class="btn btn-secondary" style="margin-top: 1.5rem;">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection
