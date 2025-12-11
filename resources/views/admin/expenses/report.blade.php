@extends('layouts.admin')

@section('title', 'Laporan Pengeluaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Pengeluaran</h3>
        <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form action="{{ route('admin.expenses.report') }}" method="GET" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
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
                <div class="stat-icon danger"><i class="bi bi-arrow-up-circle"></i></div>
                <div>
                    <div class="stat-value" style="color: var(--danger);">Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</div>
                    <div class="stat-label">Total Pengeluaran</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon info"><i class="bi bi-receipt"></i></div>
                <div>
                    <div class="stat-value">{{ $expenses->count() }}</div>
                    <div class="stat-label">Jumlah Transaksi</div>
                </div>
            </div>
        </div>

        <!-- By Category -->
        @if(($byCategory ?? collect())->count() > 0)
        <h4 style="color: var(--white); margin-bottom: 1rem;">Ringkasan per Kategori</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            @foreach($byCategory as $catData)
                <div style="background: rgba(30, 41, 59, 0.6); padding: 1rem; border-radius: 12px; border: 1px solid rgba(99, 102, 241, 0.1);">
                    <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">{{ $catData['category'] }} ({{ $catData['count'] }})</div>
                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--danger);">Rp {{ number_format($catData['total'], 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Expense List -->
        <h4 style="color: var(--white); margin-bottom: 1rem;">Detail Pengeluaran</h4>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_number }}</td>
                            <td>{{ $expense->expense_date->format('d M Y') }}</td>
                            <td><span class="badge badge-info">{{ $expense->category->name ?? '-' }}</span></td>
                            <td>{{ Str::limit($expense->description, 40) }}</td>
                            <td class="amount negative">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; padding: 2rem;">Tidak ada data pengeluaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
