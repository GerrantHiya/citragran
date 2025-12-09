@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Keuangan Bulanan</h3>
        <form action="{{ route('admin.financial-reports.generate') }}" method="POST" style="display:flex;gap:0.5rem;">
            @csrf
            <select name="month" class="form-control" style="width:auto;">
                @for($m=1;$m<=12;$m++)<option value="{{ $m }}" {{ date('m')==$m?'selected':'' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>@endfor
            </select>
            <select name="year" class="form-control" style="width:auto;">
                @for($y=date('Y');$y>=2020;$y--)<option value="{{ $y }}">{{ $y }}</option>@endfor
            </select>
            <button type="submit" class="btn btn-primary"><i class="bi bi-lightning"></i> Generate</button>
        </form>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Pendapatan</th>
                    <th>Pengeluaran</th>
                    <th>Gaji</th>
                    <th>Saldo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td><strong style="color:var(--white);">{{ $report->period_name }}</strong></td>
                        <td class="amount positive">Rp {{ number_format($report->total_income, 0, ',', '.') }}</td>
                        <td class="amount negative">Rp {{ number_format($report->total_expense, 0, ',', '.') }}</td>
                        <td class="amount negative">Rp {{ number_format($report->total_payroll, 0, ',', '.') }}</td>
                        <td class="amount" style="color:{{ $report->balance >= 0 ? 'var(--success)' : 'var(--danger)' }}">Rp {{ number_format($report->balance, 0, ',', '.') }}</td>
                        <td>
                            @if($report->is_published)<span class="badge badge-success">Published</span>@else<span class="badge badge-warning">Draft</span>@endif
                        </td>
                        <td>
                            <a href="{{ route('admin.financial-reports.show', $report) }}" class="action-btn view"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:2rem;">Belum ada laporan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($reports->hasPages())<div class="pagination" style="margin-top:1rem;">{{ $reports->links() }}</div>@endif
@endsection
