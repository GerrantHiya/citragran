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

            <!-- Bill Items -->
            <h4 style="color: var(--white); margin: 1.5rem 0 1rem;">Item Tagihan (Berlaku untuk Semua Warga)</h4>
            <div style="background: rgba(15, 23, 42, 0.5); border-radius: 12px; padding: 1.5rem;">
                @foreach($billingTypes as $index => $type)
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">{{ $type->name }}</label>
                            <input type="hidden" name="items[{{ $index }}][billing_type_id]" value="{{ $type->id }}">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="items[{{ $index }}][amount]" class="form-control item-amount" value="{{ $type->default_amount }}" required>
                        </div>
                    </div>
                @endforeach

                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 2px solid rgba(99, 102, 241, 0.3);">
                    <span style="font-size: 1.125rem; font-weight: 600; color: var(--white);">Total per Warga:</span>
                    <span id="totalAmount" style="font-size: 1.5rem; font-weight: 700; color: var(--primary-light);">Rp 0</span>
                </div>
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
