@extends('layouts.admin')

@section('title', 'Detail Hutang')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $employeeDebt->debt_number }}</h3>
            @if($employeeDebt->status === 'active')<span class="badge badge-warning">Aktif</span>
            @elseif($employeeDebt->status === 'paid')<span class="badge badge-success">Lunas</span>
            @else<span class="badge badge-danger">Dibatalkan</span>@endif
        </div>
        <div class="card-body">
            <div style="margin-bottom:1rem;"><label style="color:var(--gray-500);font-size:0.75rem;">KARYAWAN</label><p style="color:var(--white);">{{ $employeeDebt->employee->name }}</p></div>
            <div style="margin-bottom:1rem;"><label style="color:var(--gray-500);font-size:0.75rem;">DESKRIPSI</label><p style="color:var(--gray-300);">{{ $employeeDebt->description }}</p></div>
            <table class="table">
                <tr><td>Jumlah Hutang</td><td style="text-align:right;">Rp {{ number_format($employeeDebt->amount, 0, ',', '.') }}</td></tr>
                <tr><td>Terbayar</td><td style="text-align:right;" class="amount positive">Rp {{ number_format($employeeDebt->paid_amount, 0, ',', '.') }}</td></tr>
                <tr style="background:rgba(239,68,68,0.1);"><td style="font-weight:700;">Sisa</td><td style="text-align:right;font-weight:700;color:var(--danger);">Rp {{ number_format($employeeDebt->remaining_amount, 0, ',', '.') }}</td></tr>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Pembayaran</h3></div>
        <div class="card-body">
            @foreach($employeeDebt->payments as $payment)
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(99,102,241,0.1);">
                    <span>{{ $payment->payment_date->format('d M Y') }}</span>
                    <span class="amount positive">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
            @endforeach
            @if($employeeDebt->status === 'active')
                <form action="{{ route('admin.employee-debts.add-payment', $employeeDebt) }}" method="POST" style="margin-top:1rem;">
                    @csrf
                    <div class="form-group"><input type="number" name="amount" class="form-control" placeholder="Jumlah" max="{{ $employeeDebt->remaining_amount }}" required></div>
                    <div class="form-group"><input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-plus-lg"></i> Tambah Pembayaran</button>
                </form>
            @endif
        </div>
    </div>
</div>
<a href="{{ route('admin.employee-debts.index') }}" class="btn btn-secondary" style="margin-top:1.5rem;"><i class="bi bi-arrow-left"></i> Kembali</a>
@endsection
