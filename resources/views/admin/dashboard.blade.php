@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total_residents']) }}</div>
            <div class="stat-label">Total Warga Aktif</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon secondary">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['total_employees']) }}</div>
            <div class="stat-label">Total Karyawan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-receipt"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['pending_bills']) }}</div>
            <div class="stat-label">Tagihan Belum Lunas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="bi bi-chat-square-text-fill"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($stats['pending_reports']) }}</div>
            <div class="stat-label">Laporan Aktif</div>
        </div>
    </div>
</div>

<!-- Financial Summary -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
    <div class="stat-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.05));">
        <div class="stat-icon success">
            <i class="bi bi-arrow-down-circle-fill"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value" style="color: var(--success);">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
            <div class="stat-label">Pendapatan Bulan Ini</div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.05));">
        <div class="stat-icon danger">
            <i class="bi bi-arrow-up-circle-fill"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value" style="color: var(--danger);">Rp {{ number_format($monthlyExpense + $monthlyPayroll, 0, ',', '.') }}</div>
            <div class="stat-label">Pengeluaran Bulan Ini</div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.05));">
        <div class="stat-icon primary">
            <i class="bi bi-piggy-bank-fill"></i>
        </div>
        <div class="stat-content">
            @php $balance = $monthlyIncome - $monthlyExpense - $monthlyPayroll; @endphp
            <div class="stat-value" style="color: {{ $balance >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </div>
            <div class="stat-label">Saldo Bulan Ini</div>
        </div>
    </div>
</div>

<!-- Charts and Tables -->
<div class="grid grid-2" style="margin-top: 2rem;">
    <!-- Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pendapatan vs Pengeluaran</h3>
        </div>
        <div class="card-body">
            <canvas id="financeChart" height="250"></canvas>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pembayaran Terbaru</h3>
            <a href="{{ route('admin.ipl-bills.index') }}" class="btn btn-secondary btn-sm">
                Lihat Semua
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Warga</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $payment->bill->resident->name ?? '-' }}</strong>
                                        <div style="font-size: 0.75rem; color: var(--gray-500);">
                                            {{ $payment->bill->resident->block_number ?? '-' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="amount positive">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 2rem;">
                                    Belum ada pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-2" style="margin-top: 1.5rem;">
    <!-- Overdue Bills -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-exclamation-triangle-fill" style="color: var(--warning);"></i>
                Tagihan Jatuh Tempo
            </h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Warga</th>
                            <th>Periode</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overdueBills as $bill)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.ipl-bills.show', $bill) }}" style="color: var(--white); text-decoration: none;">
                                        <strong>{{ $bill->resident->name }}</strong>
                                        <div style="font-size: 0.75rem; color: var(--gray-500);">
                                            {{ $bill->resident->block_number }}
                                        </div>
                                    </a>
                                </td>
                                <td>{{ $bill->period_name }}</td>
                                <td class="amount negative">Rp {{ number_format($bill->remaining_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-danger">Jatuh Tempo</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem;">
                                    <i class="bi bi-check-circle-fill" style="color: var(--success); font-size: 1.5rem;"></i>
                                    <div style="margin-top: 0.5rem;">Tidak ada tagihan jatuh tempo</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Warga Terbaru</h3>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm">
                Lihat Semua
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tiket</th>
                            <th>Warga</th>
                            <th>Jenis</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentReports as $report)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.reports.show', $report) }}" style="color: var(--primary-light); text-decoration: none;">
                                        {{ $report->ticket_number }}
                                    </a>
                                </td>
                                <td>{{ $report->resident->name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $report->type_name }}</span>
                                </td>
                                <td>
                                    @switch($report->status)
                                        @case('received')
                                            <span class="badge badge-info">Diterima</span>
                                            @break
                                        @case('analyzing')
                                            <span class="badge badge-warning">Dianalisa</span>
                                            @break
                                        @case('processing')
                                            <span class="badge badge-primary">Diproses</span>
                                            @break
                                        @case('completed')
                                            <span class="badge badge-success">Selesai</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge badge-danger">Ditolak</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem;">
                                    Belum ada laporan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Finance Chart
    const ctx = document.getElementById('financeChart').getContext('2d');
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: chartData.income,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 0,
                    borderRadius: 6,
                },
                {
                    label: 'Pengeluaran',
                    data: chartData.expense,
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 0,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#94a3b8',
                        padding: 20,
                        font: {
                            family: 'Inter'
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(99, 102, 241, 0.1)',
                    },
                    ticks: {
                        color: '#64748b',
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            }
        }
    });
</script>
@endpush
