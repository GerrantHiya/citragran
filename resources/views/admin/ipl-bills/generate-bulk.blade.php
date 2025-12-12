@extends('layouts.admin')

@section('title', 'Generate Tagihan Bulk')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Generate Tagihan IPL + RT untuk Semua Warga</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info" style="margin-bottom: 1.5rem;">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <strong>Informasi:</strong> Fitur ini akan membuat tagihan bulanan untuk semua warga aktif.
                <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
                    <li><strong>IPL</strong> - Sesuai tarif yang sudah diset per warga</li>
                    <li><strong>Iuran RT</strong> - Sama untuk semua warga</li>
                </ul>
                Jika tagihan untuk periode tersebut sudah ada, maka akan dilewati.
            </div>
        </div>

        <form action="{{ route('admin.ipl-bills.store-bulk') }}" method="POST">
            @csrf
            
            <div class="grid grid-2" style="margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="month">Bulan <span style="color: var(--danger);">*</span></label>
                    <select id="month" name="month" class="form-control" required>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="year">Tahun <span style="color: var(--danger);">*</span></label>
                    <select id="year" name="year" class="form-control" required>
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="due_date">Jatuh Tempo <span style="color: var(--danger);">*</span></label>
                <input type="date" id="due_date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
            </div>

            <!-- Iuran RT (Sama untuk Semua) -->
            <h4 style="color: var(--white); margin: 1.5rem 0 1rem;"><i class="bi bi-people"></i> Iuran RT (Sama untuk Semua Warga)</h4>
            <div style="background: rgba(15, 23, 42, 0.5); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                @if($rtFee)
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="color: var(--white);">{{ $rtFee->name }}</strong>
                            <p style="margin: 0; color: var(--gray-400); font-size: 0.875rem;">{{ $rtFee->description }}</p>
                        </div>
                        <div style="text-align: right;">
                            <input type="hidden" name="rt_fee_amount" value="{{ $rtFee->amount }}">
                            <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary-light);">Rp {{ number_format($rtFee->amount, 0, ',', '.') }}</span>
                            <p style="margin: 0; color: var(--gray-500); font-size: 0.75rem;">per warga</p>
                        </div>
                    </div>
                @else
                    <p style="color: var(--warning); margin: 0;">
                        <i class="bi bi-exclamation-triangle"></i> Belum ada tarif iuran RT yang aktif.
                    </p>
                @endif
            </div>

            <!-- Tarif IPL per Warga -->
            <h4 style="color: var(--white); margin: 1.5rem 0 1rem;"><i class="bi bi-house"></i> IPL per Warga</h4>
            <div style="background: rgba(15, 23, 42, 0.5); border-radius: 12px; padding: 1.5rem;">
                <table class="table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>Blok</th>
                            <th>Nama Warga</th>
                            <th>Luas Tanah</th>
                            <th>IPL</th>
                            <th>Iuran RT</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $grandTotal = 0; 
                            $rtAmount = $rtFee ? $rtFee->amount : 0;
                        @endphp
                        @foreach($residents as $resident)
                            @php
                                $iplAmount = $resident->ipl_amount ?? 0;
                                $total = $iplAmount + $rtAmount;
                                $grandTotal += $total;
                            @endphp
                            <tr>
                                <td><strong style="color: var(--primary-light);">{{ $resident->block_number }}</strong></td>
                                <td>{{ $resident->name }}</td>
                                <td>
                                    @if($resident->land_area)
                                        {{ number_format($resident->land_area, 0) }} m²
                                    @else
                                        <span style="color: var(--warning);">-</span>
                                    @endif
                                </td>
                                <td class="amount">Rp {{ number_format($iplAmount, 0, ',', '.') }}</td>
                                <td class="amount">Rp {{ number_format($rtAmount, 0, ',', '.') }}</td>
                                <td class="amount" style="font-weight: 700; color: var(--success);">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: rgba(99, 102, 241, 0.1);">
                            <td colspan="5" style="text-align: right; font-weight: 700; color: var(--white);">Total Keseluruhan:</td>
                            <td style="font-size: 1.125rem; font-weight: 700; color: var(--success);">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div style="background: rgba(245, 158, 11, 0.1); border-radius: 12px; padding: 1rem; margin-top: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--warning);">
                    <i class="bi bi-people-fill" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>{{ $residents->count() }} Warga Aktif</strong>
                        <p style="margin: 0; font-size: 0.875rem; opacity: 0.8;">
                            Akan dibuat tagihan untuk semua warga di atas
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin membuat tagihan untuk semua warga aktif?')">
                    <i class="bi bi-lightning-fill"></i>
                    Generate Tagihan
                </button>
                <a href="{{ route('admin.ipl-bills.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
