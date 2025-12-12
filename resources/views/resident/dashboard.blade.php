@extends('layouts.resident')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Selamat Datang, {{ $resident->name }}</h1>
    <p class="page-subtitle">Blok {{ $resident->block_number }}</p>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-receipt"></i>
        </div>
        <div>
            <div class="stat-value">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</div>
            <div class="stat-label">Total Tunggakan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <div class="stat-value">{{ $unpaidBills->count() }}</div>
            <div class="stat-label">Tagihan Belum Lunas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-chat-square-text"></i>
        </div>
        <div>
            <div class="stat-value">{{ $activeReports->count() }}</div>
            <div class="stat-label">Laporan Aktif</div>
        </div>
    </div>
</div>

<div class="grid grid-2">
    <!-- Unpaid Bills -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tagihan Belum Lunas</h3>
            <a href="{{ route('resident.bills.index') }}" class="btn btn-secondary btn-sm">
                Lihat Semua
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unpaidBills->take(5) as $bill)
                        <tr>
                            <td>
                                <a href="{{ route('resident.bills.show', $bill) }}" style="color: var(--primary); text-decoration: none;">
                                    {{ $bill->period_name }}
                                </a>
                            </td>
                            <td class="amount negative">Rp {{ number_format($bill->remaining_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($bill->status === 'overdue')
                                    <span class="badge badge-danger">Jatuh Tempo</span>
                                @elseif($bill->status === 'partial')
                                    <span class="badge badge-warning">Sebagian</span>
                                @else
                                    <span class="badge badge-info">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem;">
                                <i class="bi bi-check-circle-fill" style="color: var(--success); font-size: 1.5rem;"></i>
                                <div style="margin-top: 0.5rem;">Tidak ada tagihan belum lunas</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active Reports -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Aktif</h3>
            <a href="{{ route('resident.reports.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                Buat Laporan
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tiket</th>
                        <th>Subjek</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeReports as $report)
                        <tr>
                            <td>
                                <a href="{{ route('resident.reports.show', $report) }}" style="color: var(--primary); text-decoration: none;">
                                    {{ $report->ticket_number }}
                                </a>
                            </td>
                            <td>{{ Str::limit($report->subject, 25) }}</td>
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
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem;">
                                Tidak ada laporan aktif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Announcements -->
@if($announcements->count() > 0)
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Pengumuman Terbaru</h3>
        <a href="{{ route('resident.announcements') }}" class="btn btn-secondary btn-sm">
            Lihat Semua
        </a>
    </div>
    <div class="card-body">
        @foreach($announcements as $announcement)
            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                    <h4 style="font-size: 1rem; color: var(--text-primary); font-weight: 600;">{{ $announcement->title }}</h4>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $announcement->published_at?->format('d M Y') ?? $announcement->created_at->format('d M Y') }}</span>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.6;">
                    {{ Str::limit(strip_tags($announcement->content), 150) }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Financial Reports -->
@if($financialReports->count() > 0)
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Laporan Keuangan Bulanan</h3>
        <a href="{{ route('resident.financial-reports.index') }}" class="btn btn-secondary btn-sm">
            Lihat Semua
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Pendapatan</th>
                    <th>Pengeluaran</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($financialReports as $report)
                    <tr>
                        <td>
                            <a href="{{ route('resident.financial-reports.show', $report) }}" style="color: var(--primary); text-decoration: none;">
                                {{ $report->period_name }}
                            </a>
                        </td>
                        <td class="amount positive">Rp {{ number_format($report->total_income, 0, ',', '.') }}</td>
                        <td class="amount negative">Rp {{ number_format($report->total_expense + $report->total_payroll, 0, ',', '.') }}</td>
                        <td class="amount" style="color: {{ $report->balance >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                            Rp {{ number_format($report->balance, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
