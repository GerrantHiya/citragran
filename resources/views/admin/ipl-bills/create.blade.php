@extends('layouts.admin')

@section('title', 'Tambah Tagihan IPL')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Tagihan IPL</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.ipl-bills.store') }}" method="POST" id="billForm">
            @csrf
            
            <div class="grid grid-2" style="margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="resident_id">Warga <span style="color: var(--danger);">*</span></label>
                    <select id="resident_id" name="resident_id" class="form-control" required>
                        <option value="">Pilih Warga</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}" {{ old('resident_id') == $resident->id ? 'selected' : '' }}>
                                {{ $resident->block_number }} - {{ $resident->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="due_date">Jatuh Tempo <span style="color: var(--danger);">*</span></label>
                    <input type="date" id="due_date" name="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                </div>
            </div>

            <div class="grid grid-2" style="margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" for="month">Bulan <span style="color: var(--danger);">*</span></label>
                    <select id="month" name="month" class="form-control" required>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('month', date('m')) == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="year">Tahun <span style="color: var(--danger);">*</span></label>
                    <select id="year" name="year" class="form-control" required>
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Bill Items -->
            <h4 style="color: var(--white); margin-bottom: 1rem;">Item Tagihan</h4>
            <div id="billItems" style="background: rgba(15, 23, 42, 0.5); border-radius: 12px; padding: 1.5rem;">
                @foreach($billingTypes as $index => $type)
                    <div class="bill-item" style="display: grid; grid-template-columns: 2fr 1fr 1fr repeat(2, 1fr); gap: 1rem; align-items: end; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">{{ $type->name }}</label>
                            <input type="hidden" name="items[{{ $index }}][billing_type_id]" value="{{ $type->id }}">
                            <input type="text" value="{{ $type->name }}" class="form-control" disabled style="opacity: 0.7;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="items[{{ $index }}][amount]" class="form-control item-amount" value="{{ old("items.{$index}.amount", $type->default_amount) }}" required>
                        </div>
                        @if($type->code === 'water' || $type->code === 'pam')
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Meter Sebelum</label>
                                <input type="number" name="items[{{ $index }}][meter_previous]" class="form-control" value="{{ old("items.{$index}.meter_previous") }}">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Meter Sekarang</label>
                                <input type="number" name="items[{{ $index }}][meter_current]" class="form-control" value="{{ old("items.{$index}.meter_current") }}">
                            </div>
                        @else
                            <div></div>
                            <div></div>
                        @endif
                        <div></div>
                    </div>
                @endforeach

                <!-- Total -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 2px solid rgba(99, 102, 241, 0.3);">
                    <span style="font-size: 1.125rem; font-weight: 600; color: var(--white);">Total Tagihan:</span>
                    <span id="totalAmount" style="font-size: 1.5rem; font-weight: 700; color: var(--primary-light);">Rp 0</span>
                </div>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label class="form-label" for="notes">Catatan (Opsional)</label>
                <textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Simpan Tagihan
                </button>
                <a href="{{ route('admin.ipl-bills.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-amount').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('totalAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    document.querySelectorAll('.item-amount').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    calculateTotal();
</script>
@endpush
@endsection
