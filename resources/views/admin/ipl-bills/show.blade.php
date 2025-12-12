@extends('layouts.admin')

@section('title', 'Detail Tagihan IPL')

@section('content')
<div class="grid" style="gap: 1.5rem;">
    <!-- Bill Info -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">{{ $iplBill->bill_number }}</h3>
                <p style="color: var(--gray-500); margin: 0.25rem 0 0 0; font-size: 0.875rem;">
                    Periode: {{ $iplBill->period_name }}
                </p>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                @switch($iplBill->status)
                    @case('paid')
                        <span class="badge badge-success" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-check-circle-fill"></i> Lunas
                        </span>
                        @break
                    @case('partial')
                        <span class="badge badge-warning" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                            Sebagian Terbayar
                        </span>
                        @break
                    @case('overdue')
                        <span class="badge badge-danger" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-exclamation-triangle-fill"></i> Jatuh Tempo
                        </span>
                        @break
                    @default
                        <span class="badge badge-info" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                            Pending
                        </span>
                @endswitch
                <a href="{{ route('admin.pdf.bill-detail', $iplBill) }}" class="btn btn-success btn-sm" target="_blank">
                    <i class="bi bi-printer"></i>
                    Cetak
                </a>
                @if($iplBill->status !== 'paid')
                    <a href="{{ route('admin.ipl-bills.edit', $iplBill) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">Nama Warga</label>
                    <p style="margin: 0.25rem 0 0; font-size: 1.125rem; font-weight: 600; color: var(--white);">
                        {{ $iplBill->resident->name }}
                    </p>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">Blok</label>
                    <p style="margin: 0.25rem 0 0; font-size: 1.125rem; font-weight: 600; color: var(--primary-light);">
                        {{ $iplBill->resident->block_number }}
                    </p>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">Jatuh Tempo</label>
                    <p style="margin: 0.25rem 0 0; font-size: 1.125rem; font-weight: 600; color: {{ $iplBill->is_overdue ? 'var(--danger)' : 'var(--white)' }};">
                        {{ $iplBill->due_date->format('d M Y') }}
                    </p>
                </div>
            </div>

            <!-- Bill Items -->
            <h4 style="font-size: 1rem; margin-bottom: 1rem; color: var(--gray-300);">Rincian Tagihan</h4>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Meteran Sebelum</th>
                            <th>Meteran Saat Ini</th>
                            <th>Pemakaian</th>
                            <th style="text-align: right;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($iplBill->items as $item)
                            <tr>
                                <td><strong>{{ $item->billingType->name }}</strong></td>
                                <td>{{ $item->meter_previous ?? '-' }}</td>
                                <td>{{ $item->meter_current ?? '-' }}</td>
                                <td>{{ $item->usage ?? '-' }}</td>
                                <td style="text-align: right;" class="amount">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: rgba(99, 102, 241, 0.1);">
                            <td colspan="4" style="font-weight: 700; color: var(--white);">Total</td>
                            <td style="text-align: right; font-weight: 700; font-size: 1.125rem; color: var(--white);">
                                Rp {{ number_format($iplBill->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="color: var(--success);">Terbayar</td>
                            <td style="text-align: right;" class="amount positive">
                                Rp {{ number_format($iplBill->paid_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr style="background: rgba(239, 68, 68, 0.1);">
                            <td colspan="4" style="font-weight: 700; color: var(--white);">Sisa Tagihan</td>
                            <td style="text-align: right; font-weight: 700; font-size: 1.125rem; color: var(--danger);">
                                Rp {{ number_format($iplBill->remaining_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-2">
        <!-- Payment History -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pembayaran</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Pembayaran</th>
                                <th>Tanggal</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($iplBill->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_number }}</td>
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
                                    <td colspan="4" style="text-align: center; padding: 2rem;">
                                        Belum ada pembayaran
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Payment Form -->
        @if($iplBill->status !== 'paid')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pembayaran</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ipl-bills.add-payment', $iplBill) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="amount">Jumlah Pembayaran</label>
                            <input type="number" id="amount" name="amount" class="form-control" max="{{ $iplBill->remaining_amount }}" required>
                            <small style="color: var(--gray-500);">Maksimal: Rp {{ number_format($iplBill->remaining_amount, 0, ',', '.') }}</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="payment_date">Tanggal Pembayaran</label>
                            <input type="date" id="payment_date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="payment_method">Metode Pembayaran</label>
                            <select id="payment_method" name="payment_method" class="form-control" required>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="reference_number">No. Referensi (Opsional)</label>
                            <input type="text" id="reference_number" name="reference_number" class="form-control" placeholder="No. transfer / bukti">
                        </div>
                        <button type="submit" class="btn btn-success" style="width: 100%;">
                            <i class="bi bi-check-lg"></i>
                            Simpan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<div style="margin-top: 1.5rem;">
    <a href="{{ route('admin.ipl-bills.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>
</div>
@endsection
