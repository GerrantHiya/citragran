@extends('layouts.resident')

@section('title', 'Akun Belum Terhubung')

@section('content')
<div style="max-width: 500px; margin: 4rem auto; text-align: center;">
    <div style="width: 100px; height: 100px; background: rgba(245, 158, 11, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
        <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem; color: var(--warning);"></i>
    </div>
    
    <h1 class="page-title">Akun Belum Terhubung</h1>
    <p style="color: var(--gray-400); margin-bottom: 2rem; line-height: 1.7;">
        Akun Anda belum terhubung dengan data warga. Silakan hubungi administrator perumahan untuk menghubungkan akun Anda dengan data warga.
    </p>
    
    <div class="card">
        <div class="card-body">
            <h4 style="color: var(--white); margin-bottom: 1rem;">Informasi Akun Anda</h4>
            <div style="text-align: left;">
                <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                    <span style="color: var(--gray-500);">Nama</span>
                    <span style="color: var(--white);">{{ auth()->user()->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                    <span style="color: var(--gray-500);">Email</span>
                    <span style="color: var(--white);">{{ auth()->user()->email }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                    <span style="color: var(--gray-500);">Telepon</span>
                    <span style="color: var(--white);">{{ auth()->user()->phone ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 2rem;">
        <a href="{{ route('profile') }}" class="btn btn-secondary">
            <i class="bi bi-pencil"></i>
            Edit Profil
        </a>
    </div>
</div>
@endsection
