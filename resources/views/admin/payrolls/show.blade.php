@extends('layouts.admin')

@section('title', 'Detail Payroll')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $payroll->payroll_number }}</h3>
            @switch($payroll->status)
                @case('pending')<span class="badge badge-warning">Pending</span>@break
                @case('approved')<span class="badge badge-info">Disetujui</span>@break
                @case('paid')<span class="badge badge-success">Dibayar</span>@break
                @case('cancelled')<span class="badge badge-danger">Dibatalkan</span>@break
            @endswitch
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                <div><label style="color:var(--gray-500);font-size:0.75rem;">KARYAWAN</label><p style="color:var(--white);">{{ $payroll->employee->name }}</p></div>
                <div><label style="color:var(--gray-500);font-size:0.75rem;">PERIODE</label><p style="color:var(--white);">{{ $payroll->period_name }}</p></div>
            </div>
            <table class="table">
                <tr><td>Gaji Pokok</td><td style="text-align:right;">Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</td></tr>
                <tr><td>Bonus</td><td style="text-align:right;" class="amount positive">+ Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</td></tr>
                <tr><td>Potongan</td><td style="text-align:right;" class="amount negative">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</td></tr>
                <tr style="background:rgba(99,102,241,0.1);"><td style="font-weight:700;">Total</td><td style="text-align:right;font-weight:700;font-size:1.125rem;color:var(--white);">Rp {{ number_format($payroll->total_amount, 0, ',', '.') }}</td></tr>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Aksi</h3></div>
        <div class="card-body">
            @if($payroll->status === 'pending')
                <form action="{{ route('admin.payrolls.approve', $payroll) }}" method="POST" style="margin-bottom:1rem;">
                    @csrf
                    <button type="submit" class="btn btn-success" style="width:100%;"><i class="bi bi-check-lg"></i> Setujui</button>
                </form>
            @endif
            @if($payroll->status === 'approved')
                <form action="{{ route('admin.payrolls.pay', $payroll) }}" method="POST" style="margin-bottom:1rem;">
                    @csrf
                    <div class="form-group"><label class="form-label">Tanggal Bayar</label><input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                    <button type="submit" class="btn btn-primary" style="width:100%;"><i class="bi bi-wallet2"></i> Bayar</button>
                </form>
            @endif
            @if($payroll->status !== 'paid' && $payroll->status !== 'cancelled')
                <form action="{{ route('admin.payrolls.cancel', $payroll) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width:100%;" onclick="return confirm('Yakin?')"><i class="bi bi-x-lg"></i> Batalkan</button>
                </form>
            @endif
        </div>
    </div>
</div>
<a href="{{ route('admin.payrolls.index') }}" class="btn btn-secondary" style="margin-top:1.5rem;"><i class="bi bi-arrow-left"></i> Kembali</a>
@endsection
