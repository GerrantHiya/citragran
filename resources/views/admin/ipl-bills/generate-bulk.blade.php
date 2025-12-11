@extends('layouts.admin')

@section('title', 'Generate Tagihan Bulk')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Generate Tagihan IPL untuk Semua Warga</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info" style="margin-bottom: 1.5rem;">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <strong>Informasi:</strong> Fitur ini akan membuat tagihan IPL untuk semua warga aktif. 
                Tagihan tetap (Kebersihan, Security, Sampah) sama untuk semua warga, sedangkan meteran air berbeda tiap warga.
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

            <!-- Fixed Bill Items (Same for all) -->
            <h4 style="color: var(--white); margin: 1.5rem 0 1rem;"><i class="bi bi-pin-angle"></i> Tagihan Tetap (Sama untuk Semua Warga)</h4>
            <div style="background: rgba(15, 23, 42, 0.5); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                @foreach($billingTypes as $index => $type)
                    @if($type->code !== 'water')
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">{{ $type->name }}</label>
                                <input type="hidden" name="fixed_items[{{ $type->id }}][billing_type_id]" value="{{ $type->id }}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="fixed_items[{{ $type->id }}][amount]" class="form-control fixed-amount" value="{{ $type->default_amount }}" required>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 2px solid rgba(99, 102, 241, 0.3);">
                    <span style="font-size: 1rem; font-weight: 600; color: var(--gray-400);">Subtotal Tagihan Tetap:</span>
                    <span id="fixedTotal" style="font-size: 1.25rem; font-weight: 700; color: var(--primary-light);">Rp 0</span>
                </div>
            </div>

            <!-- Water Meter for Each Resident -->
            @php $waterType = $billingTypes->firstWhere('code', 'water'); @endphp
            @if($waterType)
            <h4 style="color: var(--white); margin: 1.5rem 0 1rem;"><i class="bi bi-droplet"></i> Meteran Air PAM (Per Warga)</h4>
            <div style="background: rgba(15, 23, 42, 0.5); border-radius: 12px; padding: 1.5rem;">
                <table class="table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>Blok</th>
                            <th>Nama Warga</th>
                            <th>Meter Lama</th>
                            <th>Meter Baru</th>
                            <th>Pemakaian</th>
                            <th>Harga/m³</th>
                            <th>Total Air</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($residents as $ri => $resident)
                            <tr>
                                <td><strong style="color: var(--primary-light);">{{ $resident->block_number }}</strong></td>
                                <td>{{ $resident->name }}</td>
                                <td>
                                    <input type="hidden" name="water[{{ $resident->id }}][billing_type_id]" value="{{ $waterType->id }}">
                                    <input type="number" name="water[{{ $resident->id }}][meter_prev]" class="form-control meter-prev" data-resident="{{ $resident->id }}" placeholder="0" value="0" style="width: 100px;">
                                </td>
                                <td>
                                    <input type="number" name="water[{{ $resident->id }}][meter_current]" class="form-control meter-current" data-resident="{{ $resident->id }}" placeholder="0" value="0" style="width: 100px;">
                                </td>
                                <td id="usage-{{ $resident->id }}" style="font-weight: 600; color: var(--info);">0 m³</td>
                                <td>
                                    <input type="number" name="water[{{ $resident->id }}][price_per_unit]" class="form-control price-unit" data-resident="{{ $resident->id }}" value="{{ $waterType->default_amount }}" style="width: 100px;">
                                </td>
                                <td id="water-total-{{ $resident->id }}" class="amount" style="font-weight: 700; color: var(--success);">Rp 0</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <div style="background: rgba(99, 102, 241, 0.1); border-radius: 12px; padding: 1.5rem; margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="color: var(--white); font-size: 1rem;"><i class="bi bi-people-fill"></i> {{ $residents->count() }} Warga Aktif</strong>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--gray-400);">Akan dibuat tagihan untuk semua warga di atas</p>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.875rem; color: var(--gray-400);">Total Keseluruhan (Estimasi)</div>
                        <div id="grandTotal" style="font-size: 1.75rem; font-weight: 700; color: var(--success);">Rp 0</div>
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

<script>
    function calculateTotals() {
        // Calculate fixed items total
        let fixedTotal = 0;
        document.querySelectorAll('.fixed-amount').forEach(input => {
            fixedTotal += parseFloat(input.value) || 0;
        });
        document.getElementById('fixedTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(fixedTotal);

        // Calculate water totals for each resident
        let totalWater = 0;
        @foreach($residents as $resident)
        (function() {
            const residentId = {{ $resident->id }};
            const prevEl = document.querySelector('.meter-prev[data-resident="' + residentId + '"]');
            const currEl = document.querySelector('.meter-current[data-resident="' + residentId + '"]');
            const priceEl = document.querySelector('.price-unit[data-resident="' + residentId + '"]');
            
            if (prevEl && currEl && priceEl) {
                const prev = parseFloat(prevEl.value) || 0;
                const curr = parseFloat(currEl.value) || 0;
                const price = parseFloat(priceEl.value) || 0;
                const usage = Math.max(0, curr - prev);
                const waterTotal = usage * price;
                
                document.getElementById('usage-' + residentId).textContent = usage + ' m³';
                document.getElementById('water-total-' + residentId).textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(waterTotal);
                totalWater += waterTotal;
            }
        })();
        @endforeach

        // Calculate grand total (fixed * residents + total water)
        const residentCount = {{ $residents->count() }};
        const grandTotal = (fixedTotal * residentCount) + totalWater;
        document.getElementById('grandTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
    }

    // Add event listeners to all inputs
    document.querySelectorAll('.fixed-amount, .meter-prev, .meter-current, .price-unit').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    // Initial calculation
    calculateTotals();
</script>
@endsection
