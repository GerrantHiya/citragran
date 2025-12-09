@extends('layouts.resident')

@section('title', 'Detail Laporan Keuangan')

@section('content')
<div class="page-header">
    <a href="{{ route('resident.financial-reports.index') }}" style="color: var(--gray-400); text-decoration: none;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h1 class="page-title">Laporan Keuangan {{ $financialReport->period_name }}</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon success"><i class="bi bi-arrow-down-circle"></i></div>
        <div>
            <div class="stat-value" style="color: var(--success);">Rp {{ number_format($financialReport->total_income, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="bi bi-arrow-up-circle"></i></div>
        <div>
            <div class="stat-value" style="color: var(--danger);">Rp {{ number_format($financialReport->total_expense + $financialReport->total_payroll, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pengeluaran</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="bi bi-piggy-bank"></i></div>
        <div>
            <div class="stat-value" style="color: {{ $financialReport->balance >= 0 ? 'var(--success)' : 'var(--danger)' }};">Rp {{ number_format($financialReport->balance, 0, ',', '.') }}</div>
            <div class="stat-label">Saldo</div>
        </div>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pendapatan</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                <span style="color: var(--gray-400);">Pendapatan IPL</span>
                <span class="amount positive">Rp {{ number_format($financialReport->total_income, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pengeluaran</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                <span style="color: var(--gray-400);">Pengeluaran Operasional</span>
                <span class="amount negative">Rp {{ number_format($financialReport->total_expense, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem 0;">
                <span style="color: var(--gray-400);">Gaji Karyawan</span>
                <span class="amount negative">Rp {{ number_format($financialReport->total_payroll, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

@if($financialReport->summary)
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">Ringkasan</h3>
        </div>
        <div class="card-body">
            <p style="color: var(--gray-300); line-height: 1.7;">{!! nl2br(e($financialReport->summary)) !!}</p>
        </div>
    </div>
@endif
@endsection
