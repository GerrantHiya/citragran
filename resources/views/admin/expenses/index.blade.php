@extends('layouts.admin')

@section('title', 'Pengeluaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengeluaran</h3>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.expenses.report') }}" class="btn btn-secondary"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_number }}</td>
                        <td>{{ $expense->expense_date->format('d M Y') }}</td>
                        <td><span class="badge badge-info">{{ $expense->category->name ?? '-' }}</span></td>
                        <td>{{ Str::limit($expense->description, 30) }}</td>
                        <td class="amount negative">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        <td>
                            @switch($expense->status)
                                @case('pending')<span class="badge badge-warning">Pending</span>@break
                                @case('approved')<span class="badge badge-success">Disetujui</span>@break
                                @case('rejected')<span class="badge badge-danger">Ditolak</span>@break
                            @endswitch
                        </td>
                        <td><a href="{{ route('admin.expenses.show', $expense) }}" class="action-btn view"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:2rem;">Belum ada pengeluaran</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($expenses->hasPages())<div class="pagination" style="margin-top:1rem;">{{ $expenses->links() }}</div>@endif
@endsection
