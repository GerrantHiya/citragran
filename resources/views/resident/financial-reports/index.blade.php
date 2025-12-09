@extends('layouts.resident')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="page-header">
    <h1 class="page-title">Laporan Keuangan</h1>
    <p class="page-subtitle">Transparansi keuangan perumahan</p>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Pendapatan</th>
                    <th>Pengeluaran</th>
                    <th>Saldo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td><strong style="color: var(--white);">{{ $report->period_name }}</strong></td>
                        <td class="amount positive">Rp {{ number_format($report->total_income, 0, ',', '.') }}</td>
                        <td class="amount negative">Rp {{ number_format($report->total_expense + $report->total_payroll, 0, ',', '.') }}</td>
                        <td class="amount" style="color: {{ $report->balance >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                            Rp {{ number_format($report->balance, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('resident.financial-reports.show', $report) }}" class="btn btn-secondary btn-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">
                            Belum ada laporan keuangan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($reports->hasPages())
    <div style="margin-top: 1rem;">{{ $reports->links() }}</div>
@endif
@endsection
