@extends('layouts.resident')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="page-header">
    <a href="{{ route('resident.bills.index') }}" style="color: var(--gray-400); text-decoration: none;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h1 class="page-title">Riwayat Pembayaran</h1>
    <p class="page-subtitle">Tagihan yang sudah lunas</p>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>No. Tagihan</th>
                    <th>Total</th>
                    <th>Tgl Lunas</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bills as $bill)
                    <tr>
                        <td><strong style="color: var(--white);">{{ $bill->period_name }}</strong></td>
                        <td>{{ $bill->bill_number }}</td>
                        <td class="amount">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                        <td>{{ $bill->paid_date?->format('d M Y') ?? '-' }}</td>
                        <td>
                            <a href="{{ route('resident.bills.show', $bill) }}" class="btn btn-secondary btn-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">Belum ada riwayat pembayaran</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($bills->hasPages())
    <div style="margin-top: 1rem;">{{ $bills->links() }}</div>
@endif
@endsection
