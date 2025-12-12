@extends('layouts.admin')

@section('title', 'Laporan Penggajian')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Penggajian</h3>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.pdf.payroll-report', request()->query()) }}" class="btn btn-success" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Download PDF
            </a>
            <a href="{{ route('admin.payrolls.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form action="{{ route('admin.payrolls.report') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Periode</label>
                    <select name="period_type" class="form-control">
                        <option value="">Semua</option>
                        <option value="daily" {{ request('period_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ request('period_type') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ request('period_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>

        <!-- Summary -->
        <div class="stats-grid" style="margin-bottom: 2rem;">
            <div class="stat-card">
                <div class="stat-icon warning"><i class="bi bi-wallet2"></i></div>
                <div>
                    <div class="stat-value" style="color: var(--warning);">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</div>
                    <div class="stat-label">Total Penggajian</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon success"><i class="bi bi-plus-circle"></i></div>
                <div>
                    <div class="stat-value" style="color: var(--success);">Rp {{ number_format($summary['total_bonus'], 0, ',', '.') }}</div>
                    <div class="stat-label">Total Bonus</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon danger"><i class="bi bi-dash-circle"></i></div>
                <div>
                    <div class="stat-value" style="color: var(--danger);">Rp {{ number_format($summary['total_deductions'] + $summary['total_debt_deductions'], 0, ',', '.') }}</div>
                    <div class="stat-label">Total Potongan</div>
                </div>
            </div>
        </div>

        <!-- Payroll List -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Payroll</th>
                        <th>Karyawan</th>
                        <th>Periode</th>
                        <th>Gaji Pokok</th>
                        <th>Bonus</th>
                        <th>Potongan</th>
                        <th>Total</th>
                        <th>Tgl Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td><a href="{{ route('admin.payrolls.show', $payroll) }}" style="color: var(--primary-light);">{{ $payroll->payroll_number }}</a></td>
                            <td>{{ $payroll->employee->name }}</td>
                            <td>{{ $payroll->period_name }}</td>
                            <td>Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                            <td class="amount positive">+Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</td>
                            <td class="amount negative">-Rp {{ number_format($payroll->deductions + $payroll->debt_deduction, 0, ',', '.') }}</td>
                            <td class="amount" style="font-weight: 700;">Rp {{ number_format($payroll->total_amount, 0, ',', '.') }}</td>
                            <td>{{ $payroll->payment_date?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align: center; padding: 2rem;">Tidak ada data penggajian</td></tr>
                    @endforelse
                </tbody>
                @if($payrolls->count() > 0)
                <tfoot>
                    <tr style="background: rgba(99, 102, 241, 0.1);">
                        <td colspan="3" style="font-weight: 700; color: var(--white);">Total</td>
                        <td style="font-weight: 700;">Rp {{ number_format($summary['total_base'], 0, ',', '.') }}</td>
                        <td class="amount positive">+Rp {{ number_format($summary['total_bonus'], 0, ',', '.') }}</td>
                        <td class="amount negative">-Rp {{ number_format($summary['total_deductions'] + $summary['total_debt_deductions'], 0, ',', '.') }}</td>
                        <td style="font-weight: 700; color: var(--warning);">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
