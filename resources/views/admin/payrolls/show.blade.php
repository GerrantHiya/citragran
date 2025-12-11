@extends('layouts.admin')

@section('title', 'Detail Payroll')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $payroll->payroll_number }}</h3>
            @switch($payroll->status)
                @case('draft')<span class="badge badge-warning">Belum Dibayar</span>@break
                @case('paid')<span class="badge badge-success">Sudah Dibayar</span>@break
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
                <tr><td>Lembur</td><td style="text-align:right;" class="amount positive">+ Rp {{ number_format($payroll->overtime_pay ?? 0, 0, ',', '.') }}</td></tr>
                <tr><td>Bonus</td><td style="text-align:right;" class="amount positive">+ Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</td></tr>
                <tr><td>Potongan</td><td style="text-align:right;" class="amount negative">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</td></tr>
                <tr><td>Potongan Hutang</td><td style="text-align:right;" class="amount negative">- Rp {{ number_format($payroll->debt_deduction ?? 0, 0, ',', '.') }}</td></tr>
                <tr style="background:rgba(99,102,241,0.1);"><td style="font-weight:700;">Total</td><td style="text-align:right;font-weight:700;font-size:1.125rem;color:var(--white);">Rp {{ number_format($payroll->total_amount, 0, ',', '.') }}</td></tr>
            </table>

            @if($payroll->notes)
                <div style="margin-top: 1rem; padding: 1rem; background: rgba(15, 23, 42, 0.5); border-radius: 8px;">
                    <label style="color:var(--gray-500);font-size:0.75rem;">CATATAN</label>
                    <p style="color:var(--gray-300);margin:0;">{{ $payroll->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Aksi</h3></div>
        <div class="card-body">
            @if($payroll->status === 'draft')
                <form action="{{ route('admin.payrolls.pay', $payroll) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Tanggal Pembayaran *</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bukti Pembayaran *</label>
                        <input type="file" name="payment_proof" class="form-control" accept="image/*,.pdf" required>
                        <small style="color:var(--gray-500);">Upload foto transfer/kwitansi (JPG, PNG, PDF - Maks 5MB)</small>
                        @error('payment_proof')<span style="color: var(--danger); font-size: 0.875rem; display: block;">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="btn btn-success" style="width:100%;margin-top:0.5rem;" onclick="return confirm('Pastikan data sudah benar. Lanjutkan pembayaran?')">
                        <i class="bi bi-wallet2"></i> Bayar Sekarang
                    </button>
                </form>
                
                <hr style="border-color: rgba(99,102,241,0.2); margin: 1.5rem 0;">
                
                <a href="{{ route('admin.payrolls.edit', $payroll) }}" class="btn btn-secondary" style="width:100%;margin-bottom:0.5rem;">
                    <i class="bi bi-pencil"></i> Edit Data
                </a>
                
                <form action="{{ route('admin.payrolls.cancel', $payroll) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width:100%;" onclick="return confirm('Yakin batalkan penggajian ini?')">
                        <i class="bi bi-x-lg"></i> Batalkan
                    </button>
                </form>
            @elseif($payroll->status === 'paid')
                <div style="text-align:center;padding:1rem;">
                    <i class="bi bi-check-circle-fill" style="font-size:3rem;color:var(--success);"></i>
                    <p style="color:var(--success);font-weight:600;margin:0.5rem 0;">Sudah Dibayar</p>
                    <p style="color:var(--gray-400);font-size:0.875rem;margin:0;">{{ $payroll->payment_date->format('d M Y') }}</p>
                    @if($payroll->paidBy)
                        <p style="color:var(--gray-500);font-size:0.75rem;margin:0.25rem 0 0 0;">Oleh: {{ $payroll->paidBy->name }}</p>
                    @endif
                </div>
                
                @if($payroll->payment_proof)
                    <div style="margin-top:1rem;text-align:center;">
                        <label style="color:var(--gray-500);font-size:0.75rem;display:block;margin-bottom:0.5rem;">BUKTI PEMBAYARAN</label>
                        @if(str_ends_with(strtolower($payroll->payment_proof), '.pdf'))
                            <a href="{{ asset('storage/' . $payroll->payment_proof) }}" target="_blank" class="btn btn-secondary btn-sm">
                                <i class="bi bi-file-pdf"></i> Lihat PDF
                            </a>
                        @else
                            <a href="{{ asset('storage/' . $payroll->payment_proof) }}" target="_blank">
                                <img src="{{ asset('storage/' . $payroll->payment_proof) }}" alt="Bukti Pembayaran" style="max-width:200px;border-radius:8px;border:1px solid rgba(99,102,241,0.2);">
                            </a>
                        @endif
                    </div>
                @endif
            @else
                <div style="text-align:center;padding:1rem;">
                    <i class="bi bi-x-circle-fill" style="font-size:3rem;color:var(--danger);"></i>
                    <p style="color:var(--danger);font-weight:600;margin:0.5rem 0;">Dibatalkan</p>
                </div>
            @endif
        </div>
    </div>
</div>

<a href="{{ route('admin.payrolls.index') }}" class="btn btn-secondary" style="margin-top:1.5rem;">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection
