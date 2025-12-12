@extends('layouts.resident')

@section('title', 'Tagihan IPL')

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="page-title">Tagihan IPL</h1>
        <p class="page-subtitle">Daftar tagihan iuran pengelolaan lingkungan</p>
    </div>
    <a href="{{ route('resident.bills.history') }}" class="btn btn-secondary">
        <i class="bi bi-clock-history"></i>
        Riwayat Pembayaran
    </a>
</div>

<div class="card">
    <div class="card-body">
        <!-- Filter -->
        <form action="{{ route('resident.bills.index') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div style="min-width: 150px;">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                    </select>
                </div>
                <div style="min-width: 120px;">
                    <select name="year" class="form-control">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">
                    <i class="bi bi-search"></i>
                    Filter
                </button>
            </div>
        </form>

        <!-- Bills List -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
            @forelse($bills as $bill)
                <a href="{{ route('resident.bills.show', $bill) }}" style="text-decoration: none;">
                    <div class="card" style="height: 100%;">
                        <div class="card-body">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                <div>
                                    <h4 style="color: var(--text-primary); font-size: 1.125rem; font-weight: 600;">{{ $bill->period_name }}</h4>
                                    <p style="color: var(--text-muted); font-size: 0.75rem;">{{ $bill->bill_number }}</p>
                                </div>
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
                            </div>

                            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-top: 1px solid var(--border-color);">
                                <span style="color: var(--text-muted);">Total</span>
                                <span style="color: var(--text-primary); font-weight: 600;">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($bill->status !== 'paid')
                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-top: 1px solid var(--border-color);">
                                    <span style="color: var(--text-muted);">Sisa Bayar</span>
                                    <span class="amount negative">Rp {{ number_format($bill->remaining_amount, 0, ',', '.') }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-top: 1px solid var(--border-color);">
                                    <span style="color: var(--text-muted);">Jatuh Tempo</span>
                                    <span style="{{ $bill->is_overdue ? 'color: var(--danger);' : 'color: var(--text-secondary);' }}">
                                        {{ $bill->due_date->format('d M Y') }}
                                    </span>
                                </div>
                            @else
                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-top: 1px solid var(--border-color);">
                                    <span style="color: var(--text-muted);">Tgl. Lunas</span>
                                    <span style="color: var(--success);">{{ $bill->paid_date?->format('d M Y') ?? '-' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                    <i class="bi bi-receipt" style="font-size: 4rem; color: var(--gray-400);"></i>
                    <h3 style="color: var(--text-secondary); margin-top: 1rem;">Belum ada tagihan</h3>
                </div>
            @endforelse
        </div>

        @if($bills->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $bills->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
