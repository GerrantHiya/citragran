@extends('layouts.admin')

@section('title', 'Tagihan IPL')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Tagihan IPL</h3>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.ipl-bills.generate-bulk') }}" class="btn btn-secondary">
                <i class="bi bi-lightning-fill"></i>
                Generate Bulk
            </a>
            <a href="{{ route('admin.ipl-bills.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Tambah Tagihan
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form action="{{ route('admin.ipl-bills.index') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari no. tagihan, nama warga..." value="{{ request('search') }}">
                </div>
                <div style="min-width: 130px;">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                    </select>
                </div>
                <div style="min-width: 100px;">
                    <select name="month" class="form-control">
                        <option value="">Bulan</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div style="min-width: 100px;">
                    <select name="year" class="form-control">
                        <option value="">Tahun</option>
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">
                    <i class="bi bi-search"></i>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'month', 'year']))
                    <a href="{{ route('admin.ipl-bills.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i>
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Tagihan</th>
                        <th>Warga</th>
                        <th>Periode</th>
                        <th>Total</th>
                        <th>Terbayar</th>
                        <th>Status</th>
                        <th>Jatuh Tempo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bills as $bill)
                        <tr>
                            <td>
                                <a href="{{ route('admin.ipl-bills.show', $bill) }}" style="color: var(--primary-light); text-decoration: none; font-weight: 600;">
                                    {{ $bill->bill_number }}
                                </a>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $bill->resident->name }}</strong>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                                        {{ $bill->resident->block_number }}
                                    </div>
                                </div>
                            </td>
                            <td>{{ $bill->period_name }}</td>
                            <td class="amount">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                            <td class="amount positive">Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</td>
                            <td>
                                @switch($bill->status)
                                    @case('paid')
                                        <span class="badge badge-success">Lunas</span>
                                        @break
                                    @case('partial')
                                        <span class="badge badge-warning">Sebagian</span>
                                        @break
                                    @case('overdue')
                                        <span class="badge badge-danger">Jatuh Tempo</span>
                                        @break
                                    @default
                                        <span class="badge badge-info">Pending</span>
                                @endswitch
                            </td>
                            <td>
                                <span style="{{ $bill->is_overdue ? 'color: var(--danger);' : '' }}">
                                    {{ $bill->due_date->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.ipl-bills.show', $bill) }}" class="action-btn view" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($bill->status !== 'paid')
                                        <a href="{{ route('admin.ipl-bills.edit', $bill) }}" class="action-btn edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                    @if($bill->paid_amount == 0)
                                        <form action="{{ route('admin.ipl-bills.destroy', $bill) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <div class="empty-state-title">Belum ada tagihan</div>
                                    <div class="empty-state-text">Klik tombol "Tambah Tagihan" untuk membuat tagihan baru.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bills->hasPages())
            <div class="pagination">
                {{ $bills->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
