@extends('layouts.resident')

@section('title', 'Pengumuman')

@section('content')
<div class="page-header">
    <h1 class="page-title">Pengumuman</h1>
    <p class="page-subtitle">Informasi dan pengumuman dari Management</p>
</div>

<div class="card">
    <div class="card-body">
        @forelse($announcements as $announcement)
            <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                    <h3 style="color: var(--white); font-size: 1.125rem; font-weight: 600;">{{ $announcement->title }}</h3>
                    <span style="font-size: 0.75rem; color: var(--gray-500);">{{ $announcement->published_at?->format('d M Y') }}</span>
                </div>
                <p style="color: var(--gray-300); line-height: 1.7;">{!! nl2br(e($announcement->content)) !!}</p>
            </div>
        @empty
            <div style="text-align: center; padding: 2rem;">
                <i class="bi bi-megaphone" style="font-size: 3rem; color: var(--gray-500);"></i>
                <p style="color: var(--gray-400); margin-top: 1rem;">Belum ada pengumuman</p>
            </div>
        @endforelse
    </div>
</div>

@if($announcements->hasPages())
    <div style="margin-top: 1rem;">{{ $announcements->links() }}</div>
@endif
@endsection
