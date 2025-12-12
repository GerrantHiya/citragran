@extends('layouts.admin')

@section('title', 'Penggajian')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Penggajian</h3>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.payrolls.report') }}" class="btn btn-secondary"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
            <a href="{{ route('admin.payrolls.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Payroll</a>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Payroll</th>
                    <th>Karyawan</th>
                    <th>Periode</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $payroll)
                    <tr>
                        <td><strong style="color: var(--primary);">{{ $payroll->payroll_number }}</strong></td>
                        <td>{{ $payroll->employee->name }}</td>
                        <td>{{ $payroll->period_name }}</td>
                        <td class="amount">Rp {{ number_format($payroll->total_amount, 0, ',', '.') }}</td>
                        <td>
                            @switch($payroll->status)
                                @case('draft')
                                    <span class="badge badge-warning">Belum Dibayar</span>
                                    @break
                                @case('paid')
                                    <span class="badge badge-success">Sudah Dibayar</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge badge-danger">Dibatalkan</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('admin.payrolls.show', $payroll) }}" class="action-btn view"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;">Belum ada payroll</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($payrolls->hasPages())<div class="pagination" style="margin-top:1rem;">{{ $payrolls->links() }}</div>@endif
@endsection
