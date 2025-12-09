@extends('layouts.admin')

@section('title', 'Hutang Karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Hutang Karyawan</h3>
        <a href="{{ route('admin.employee-debts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Hutang</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Hutang</th>
                    <th>Karyawan</th>
                    <th>Jumlah</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($debts as $debt)
                    <tr>
                        <td><strong style="color: var(--primary-light);">{{ $debt->debt_number }}</strong></td>
                        <td>{{ $debt->employee->name }}</td>
                        <td>Rp {{ number_format($debt->amount, 0, ',', '.') }}</td>
                        <td class="amount negative">Rp {{ number_format($debt->remaining_amount, 0, ',', '.') }}</td>
                        <td>
                            @if($debt->status === 'active')
                                <span class="badge badge-warning">Aktif</span>
                            @elseif($debt->status === 'paid')
                                <span class="badge badge-success">Lunas</span>
                            @else
                                <span class="badge badge-danger">Dibatalkan</span>
                            @endif
                        </td>
                        <td><a href="{{ route('admin.employee-debts.show', $debt) }}" class="action-btn view"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;">Belum ada hutang</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($debts->hasPages())<div class="pagination" style="margin-top:1rem;">{{ $debts->links() }}</div>@endif
@endsection
