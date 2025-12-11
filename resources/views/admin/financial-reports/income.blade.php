@extends('layouts.admin')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Pendapatan IPL</h3>
        <a href="{{ route('admin.financial-reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form action="{{ route('admin.financial-reports.income') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                @if(request()->hasAny(['start_date', 'end_date']))
                    <a href="{{ route('admin.financial-reports.income') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="stats-grid" style="margin-bottom: 2rem;">
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <div class="stat-value" style="color: var(--success);">Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}</div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $payments->count() }}</div>
                    <div class="stat-label">Jumlah Pembayaran</div>
                </div>
            </div>
        </div>

        <!-- By Payment Method -->
        @if($byMethod->count() > 0)
        <h4 style="color: var(--white); margin-bottom: 1rem;">Ringkasan per Metode Pembayaran</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            @foreach($byMethod as $method => $total)
                <div style="background: rgba(30, 41, 59, 0.6); padding: 1rem; border-radius: 12px; border: 1px solid rgba(99, 102, 241, 0.1);">
                    <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">
                        @switch($method)
                            @case('cash') Cash @break
                            @case('transfer') Transfer Bank @break
                            @case('qris') QRIS @break
                            @default {{ ucfirst($method) }}
                        @endswitch
                    </div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--success);">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Payment List -->
        <h4 style="color: var(--white); margin-bottom: 1rem;">Detail Pembayaran</h4>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Pembayaran</th>
                        <th>Tanggal</th>
                        <th>Warga</th>
                        <th>No. Tagihan</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_number }}</td>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td>{{ $payment->bill->resident->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.ipl-bills.show', $payment->bill_id) }}" style="color: var(--primary-light); text-decoration: none;">
                                    {{ $payment->bill->bill_number ?? '-' }}
                                </a>
                            </td>
                            <td>
                                @switch($payment->payment_method)
                                    @case('cash')
                                        <span class="badge badge-success">Cash</span>
                                        @break
                                    @case('transfer')
                                        <span class="badge badge-info">Transfer</span>
                                        @break
                                    @case('qris')
                                        <span class="badge badge-primary">QRIS</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="amount positive">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">
                                <i class="bi bi-inbox" style="font-size: 2rem; color: var(--gray-500);"></i>
                                <div style="margin-top: 0.5rem; color: var(--gray-400);">Tidak ada data pembayaran</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
