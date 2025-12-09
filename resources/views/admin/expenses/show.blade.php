@extends('layouts.admin')

@section('title', 'Detail Pengeluaran')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $expense->expense_number }}</h3>
            @switch($expense->status)
                @case('pending')<span class="badge badge-warning">Pending</span>@break
                @case('approved')<span class="badge badge-success">Disetujui</span>@break
                @case('rejected')<span class="badge badge-danger">Ditolak</span>@break
            @endswitch
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                <div><label style="color:var(--gray-500);font-size:0.75rem;">TANGGAL</label><p style="color:var(--white);">{{ $expense->expense_date->format('d M Y') }}</p></div>
                <div><label style="color:var(--gray-500);font-size:0.75rem;">KATEGORI</label><p><span class="badge badge-info">{{ $expense->category->name ?? '-' }}</span></p></div>
            </div>
            <div style="margin-bottom:1.5rem;"><label style="color:var(--gray-500);font-size:0.75rem;">DESKRIPSI</label><p style="color:var(--gray-300);">{{ $expense->description }}</p></div>
            <div style="background:rgba(239,68,68,0.1);padding:1rem;border-radius:12px;text-align:center;">
                <div style="font-size:1.75rem;font-weight:700;color:var(--danger);">Rp {{ number_format($expense->amount, 0, ',', '.') }}</div>
                <div style="color:var(--gray-400);">Total Pengeluaran</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Aksi</h3></div>
        <div class="card-body">
            @if($expense->status === 'pending')
                <form action="{{ route('admin.expenses.approve', $expense) }}" method="POST" style="margin-bottom:1rem;">@csrf<button type="submit" class="btn btn-success" style="width:100%;"><i class="bi bi-check-lg"></i> Setujui</button></form>
                <form action="{{ route('admin.expenses.reject', $expense) }}" method="POST">@csrf<button type="submit" class="btn btn-danger" style="width:100%;" onclick="return confirm('Yakin?')"><i class="bi bi-x-lg"></i> Tolak</button></form>
            @else
                <p style="text-align:center;color:var(--gray-500);">Tidak ada aksi yang tersedia</p>
            @endif
        </div>
    </div>
</div>
<a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary" style="margin-top:1.5rem;"><i class="bi bi-arrow-left"></i> Kembali</a>
@endsection
