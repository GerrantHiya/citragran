@extends('layouts.resident')

@section('title', 'Detail Tagihan')

@section('content')
<div class="page-header">
    <a href="{{ route('resident.bills.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h1 class="page-title">{{ $bill->bill_number }}</h1>
    <p class="page-subtitle">Periode {{ $bill->period_name }}</p>
</div>

<div class="grid grid-2">
    <!-- Bill Info -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Tagihan</h3>
            @switch($bill->status)
                @case('paid')
                    <span class="badge badge-success" style="padding: 0.5rem 1rem;">
                        <i class="bi bi-check-circle-fill"></i> Lunas
                    </span>
                    @break
                @case('partial')
                    <span class="badge badge-warning" style="padding: 0.5rem 1rem;">Sebagian Terbayar</span>
                    @break
                @case('overdue')
                    <span class="badge badge-danger" style="padding: 0.5rem 1rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i> Jatuh Tempo
                    </span>
                    @break
                @default
                    <span class="badge badge-info" style="padding: 0.5rem 1rem;">Pending</span>
            @endswitch
        </div>
        <div class="card-body">
            <div style="display: grid; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                    <span style="color: var(--text-muted);">Jatuh Tempo</span>
                    <span style="{{ $bill->is_overdue ? 'color: var(--danger);' : 'color: var(--text-primary);' }} font-weight: 600;">
                        {{ $bill->due_date->format('d M Y') }}
                    </span>
                </div>

                @if($bill->status === 'paid')
                    <div style="display: flex; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <span style="color: var(--text-muted);">Tanggal Lunas</span>
                        <span style="color: var(--success); font-weight: 600;">{{ $bill->paid_date?->format('d M Y') }}</span>
                    </div>
                @endif
            </div>

            <!-- Bill Items -->
            <h4 style="color: var(--text-primary); margin: 1.5rem 0 1rem; font-size: 1rem;">Rincian Tagihan</h4>
            <table class="table">
                <tbody>
                    @foreach($bill->items as $item)
                        <tr>
                            <td style="color: var(--text-primary);">{{ $item->billingType->name }}</td>
                            <td style="text-align: right; font-weight: 600; color: var(--text-primary);">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: var(--gray-100);">
                        <td style="font-weight: 700; color: var(--text-primary);">Total</td>
                        <td style="text-align: right; font-weight: 700; font-size: 1.125rem; color: var(--text-primary);">
                            Rp {{ number_format($bill->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($bill->status !== 'paid')
                        <tr>
                            <td style="color: var(--success);">Terbayar</td>
                            <td style="text-align: right;" class="amount positive">
                                Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr style="background: rgba(239, 68, 68, 0.1);">
                            <td style="font-weight: 700; color: var(--text-primary);">Sisa Tagihan</td>
                            <td style="text-align: right; font-weight: 700; font-size: 1.125rem; color: var(--danger);">
                                Rp {{ number_format($bill->remaining_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Payment History -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pembayaran</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bill->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
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
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                Belum ada pembayaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($bill->status !== 'paid')
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-body" style="text-align: center;">
            <h4 style="color: var(--text-primary); margin-bottom: 0.5rem;">Cara Pembayaran</h4>
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">Silakan lakukan pembayaran ke kantor Management Perumahan atau transfer ke rekening yang telah ditentukan.</p>
            <p style="color: var(--text-muted); font-size: 0.875rem;">
                Untuk informasi lebih lanjut, hubungi admin perumahan.
            </p>
        </div>
    </div>
@endif
@endsection
