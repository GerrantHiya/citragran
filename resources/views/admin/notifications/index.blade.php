@extends('layouts.admin')

@section('title', 'Broadcast Notifikasi')

@section('content')
<div class="grid" style="gap: 1.5rem;">
    <!-- Summary -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $residentsWithUnpaid->count() }}</div>
                <div class="stat-label">Warga Belum Lunas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $unpaidBills->count() }}</div>
                <div class="stat-label">Tagihan Belum Lunas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">Rp {{ number_format($unpaidBills->sum(fn($b) => $b->total_amount - $b->paid_amount), 0, ',', '.') }}</div>
                <div class="stat-label">Total Tunggakan</div>
            </div>
        </div>
    </div>

    <!-- Broadcast Buttons -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-megaphone"></i> Kirim Pengingat Tagihan</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                    <strong>Informasi:</strong> Pengingat akan dikirim ke semua warga yang memiliki tagihan belum lunas.
                </div>
            </div>

            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <form action="{{ route('admin.notifications.send-whatsapp') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Kirim pengingat WhatsApp ke {{ $residentsWithUnpaid->count() }} warga?')">
                        <i class="bi bi-whatsapp"></i>
                        Ingatkan via WhatsApp ({{ $residentsWithUnpaid->count() }} warga)
                    </button>
                </form>

                <form action="{{ route('admin.notifications.send-email') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Kirim pengingat Email ke {{ $residentsWithUnpaid->count() }} warga?')">
                        <i class="bi bi-envelope"></i>
                        Ingatkan via Email ({{ $residentsWithUnpaid->count() }} warga)
                    </button>
                </form>
            </div>

            <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(245, 158, 11, 0.1); border-radius: 8px;">
                <p style="margin: 0; color: var(--warning); font-size: 0.875rem;">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Catatan:</strong> Fitur ini masih dalam tahap infrastruktur. Implementasi pengiriman aktual memerlukan integrasi dengan API WhatsApp dan SMTP Server.
                </p>
            </div>
        </div>
    </div>

    <!-- Daftar Warga Belum Lunas -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-person-x"></i> Warga dengan Tagihan Belum Lunas</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Blok</th>
                            <th>Nama</th>
                            <th>WhatsApp</th>
                            <th>Email</th>
                            <th>Tagihan</th>
                            <th>Total Tunggakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($residentsWithUnpaid as $resident)
                            @php
                                $residentBills = $unpaidBills->where('resident_id', $resident->id);
                                $totalDue = $residentBills->sum(fn($b) => $b->total_amount - $b->paid_amount);
                            @endphp
                            <tr>
                                <td><strong style="color: var(--primary);">{{ $resident->block_number }}</strong></td>
                                <td>{{ $resident->name }}</td>
                                <td>
                                    @if($resident->whatsapp || $resident->phone)
                                        <span style="color: var(--success);"><i class="bi bi-check-circle"></i> {{ $resident->whatsapp ?: $resident->phone }}</span>
                                    @else
                                        <span style="color: var(--danger);"><i class="bi bi-x-circle"></i> Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($resident->email)
                                        <span style="color: var(--success);"><i class="bi bi-check-circle"></i> {{ $resident->email }}</span>
                                    @else
                                        <span style="color: var(--danger);"><i class="bi bi-x-circle"></i> Tidak ada</span>
                                    @endif
                                </td>
                                <td>{{ $residentBills->count() }} tagihan</td>
                                <td><span class="amount negative">Rp {{ number_format($totalDue, 0, ',', '.') }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem;">
                                    <div style="color: var(--success);">
                                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                        <p style="margin: 0.5rem 0 0;">Semua warga sudah melunasi tagihan!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Notification Logs -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-clock-history"></i> Log Notifikasi Terakhir</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Warga</th>
                            <th>Tipe</th>
                            <th>Penerima</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $log->resident?->name ?? '-' }}</td>
                                <td>
                                    @if($log->type === 'whatsapp')
                                        <span class="badge badge-success"><i class="bi bi-whatsapp"></i> WhatsApp</span>
                                    @else
                                        <span class="badge badge-primary"><i class="bi bi-envelope"></i> Email</span>
                                    @endif
                                </td>
                                <td>{{ $log->recipient }}</td>
                                <td>
                                    @switch($log->status)
                                        @case('sent')
                                            <span class="badge badge-success">Terkirim</span>
                                            @break
                                        @case('failed')
                                            <span class="badge badge-danger">Gagal</span>
                                            @break
                                        @default
                                            <span class="badge badge-warning">Pending</span>
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                    Belum ada log notifikasi
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
