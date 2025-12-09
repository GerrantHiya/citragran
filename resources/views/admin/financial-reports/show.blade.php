@extends('layouts.admin')

@section('title', 'Detail Laporan Keuangan')

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
    <div>
        <h1 class="page-title">Laporan Keuangan {{ $financialReport->period_name }}</h1>
        @if($financialReport->is_published)<span class="badge badge-success">Published</span>@else<span class="badge badge-warning">Draft</span>@endif
    </div>
    <div style="display:flex;gap:0.5rem;">
        @if(!$financialReport->is_published)
            <form action="{{ route('admin.financial-reports.publish', $financialReport) }}" method="POST">@csrf<button type="submit" class="btn btn-success"><i class="bi bi-globe"></i> Publikasikan</button></form>
        @else
            <form action="{{ route('admin.financial-reports.unpublish', $financialReport) }}" method="POST">@csrf<button type="submit" class="btn btn-warning"><i class="bi bi-eye-slash"></i> Tarik</button></form>
        @endif
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-arrow-down-circle"></i></div>
        <div><div class="stat-value" style="color:var(--success);">Rp {{ number_format($financialReport->total_income, 0, ',', '.') }}</div><div class="stat-label">Pendapatan IPL</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-arrow-up-circle"></i></div>
        <div><div class="stat-value" style="color:var(--danger);">Rp {{ number_format($financialReport->total_expense, 0, ',', '.') }}</div><div class="stat-label">Pengeluaran Operasional</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="bi bi-people"></i></div>
        <div><div class="stat-value" style="color:var(--warning);">Rp {{ number_format($financialReport->total_payroll, 0, ',', '.') }}</div><div class="stat-label">Gaji Karyawan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary"><i class="bi bi-piggy-bank"></i></div>
        <div><div class="stat-value" style="color:{{ $financialReport->balance >= 0 ? 'var(--success)' : 'var(--danger)' }};">Rp {{ number_format($financialReport->balance, 0, ',', '.') }}</div><div class="stat-label">Saldo</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Ringkasan</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.financial-reports.update-summary', $financialReport) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><textarea name="summary" class="form-control" rows="4" placeholder="Tulis ringkasan laporan...">{{ $financialReport->summary }}</textarea></div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Ringkasan</button>
        </form>
    </div>
</div>

<a href="{{ route('admin.financial-reports.index') }}" class="btn btn-secondary" style="margin-top:1.5rem;"><i class="bi bi-arrow-left"></i> Kembali</a>
@endsection
