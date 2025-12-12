@extends('layouts.admin')

@section('title', 'Detail Warga')

@section('content')
<div class="grid" style="gap: 1.5rem;">
    <!-- Profile Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Profil Warga</h3>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.residents.edit', $resident) }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-pencil"></i>
                    Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; font-weight: 700;">
                            {{ strtoupper(substr($resident->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 style="font-size: 1.5rem; margin: 0; color: var(--white);">{{ $resident->name }}</h2>
                            <p style="color: var(--primary-light); margin: 0.25rem 0;">Blok {{ $resident->block_number }}</p>
                            @if($resident->status === 'active')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="flex: 2; min-width: 300px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">Luas Tanah</label>
                            <p style="margin: 0.25rem 0 0; color: var(--primary-light); font-weight: 600;">{{ $resident->land_area ? number_format($resident->land_area, 0) . ' m²' : '-' }}</p>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">Tarif IPL/Bulan</label>
                            <p style="margin: 0.25rem 0 0; color: var(--success); font-weight: 600;">Rp {{ number_format($resident->ipl_amount ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">Telepon</label>
                            <p style="margin: 0.25rem 0 0; color: var(--gray-300);">{{ $resident->phone ?: '-' }}</p>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">WhatsApp</label>
                            <p style="margin: 0.25rem 0 0; color: var(--gray-300);">{{ $resident->whatsapp ?: '-' }}</p>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">Email</label>
                            <p style="margin: 0.25rem 0 0; color: var(--gray-300);">{{ $resident->email ?: '-' }}</p>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase;">Tanggal Masuk</label>
                            <p style="margin: 0.25rem 0 0; color: var(--gray-300);">{{ $resident->move_in_date?->format('d M Y') ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">Rp {{ number_format($resident->total_outstanding, 0, ',', '.') }}</div>
                <div class="stat-label">Total Tunggakan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $resident->iplBills->count() }}</div>
                <div class="stat-label">Total Tagihan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon secondary">
                <i class="bi bi-chat-square-text"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $resident->reports->count() }}</div>
                <div class="stat-label">Total Laporan</div>
            </div>
        </div>
    </div>

    <!-- Account Linking Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-person-badge"></i> Akun Login</h3>
        </div>
        <div class="card-body">
            @if($resident->user)
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--success), #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="bi bi-check-lg" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <p style="margin: 0; color: var(--success); font-weight: 600;">Terhubung</p>
                            <p style="margin: 0; color: var(--gray-400); font-size: 0.875rem;">{{ $resident->user->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.residents.unlink-user', $resident) }}" method="POST" onsubmit="return confirm('Yakin ingin memutus hubungan akun ini?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-x-lg"></i> Putuskan
                        </button>
                    </form>
                </div>
            @else
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--warning);">
                        <i class="bi bi-exclamation-triangle" style="font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <p style="margin: 0; color: var(--warning); font-weight: 600;">Belum Terhubung</p>
                        <p style="margin: 0; color: var(--gray-400); font-size: 0.875rem;">Warga ini belum memiliki akun login</p>
                    </div>
                </div>

                @if($unlinkedUsers->count() > 0)
                    <form action="{{ route('admin.residents.link-user', $resident) }}" method="POST" style="display: flex; gap: 0.5rem; align-items: end; flex-wrap: wrap;">
                        @csrf
                        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                            <label class="form-label">Hubungkan dengan Akun</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">-- Pilih Akun --</option>
                                @foreach($unlinkedUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-link-45deg"></i> Hubungkan
                        </button>
                    </form>
                @else
                    <p style="color: var(--gray-500); font-size: 0.875rem; margin: 0;">
                        <i class="bi bi-info-circle"></i> Tidak ada akun warga yang belum terhubung.
                    </p>
                @endif
            @endif
        </div>
    </div>

    <div class="grid grid-2">
        <!-- Bills History -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Tagihan</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resident->iplBills->take(10) as $bill)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.ipl-bills.show', $bill) }}" style="color: var(--primary-light); text-decoration: none;">
                                            {{ $bill->period_name }}
                                        </a>
                                    </td>
                                    <td>Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 2rem;">
                                        Belum ada tagihan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reports History -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Laporan</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tiket</th>
                                <th>Subjek</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resident->reports->take(10) as $report)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.reports.show', $report) }}" style="color: var(--primary-light); text-decoration: none;">
                                            {{ $report->ticket_number }}
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($report->subject, 30) }}</td>
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
                                    <td colspan="3" style="text-align: center; padding: 2rem;">
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
</div>

<div style="margin-top: 1.5rem;">
    <a href="{{ route('admin.residents.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>
</div>
@endsection
