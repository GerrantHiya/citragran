@extends('layouts.admin')

@section('title', 'Buat Payroll')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header"><h3 class="card-title">Form Penggajian</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.payrolls.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Karyawan *</label>
                <select name="employee_id" class="form-control" required id="employeeSelect">
                    <option value="">Pilih Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" data-salary="{{ $emp->base_salary }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->employee_code }} - {{ $emp->name }} ({{ $emp->type_name }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Periode *</label>
                <select name="period_type" class="form-control" required>
                    <option value="daily" {{ old('period_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="weekly" {{ old('period_type') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ old('period_type', 'monthly') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                </select>
                @error('period_type')<span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Periode *</label>
                    <input type="date" name="period_start" class="form-control" value="{{ old('period_start', date('Y-m-01')) }}" required>
                    @error('period_start')<span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Akhir Periode *</label>
                    <input type="date" name="period_end" class="form-control" value="{{ old('period_end', date('Y-m-t')) }}" required>
                    @error('period_end')<span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Gaji Pokok *</label>
                    <input type="number" name="base_salary" id="baseSalary" class="form-control" value="{{ old('base_salary', 0) }}" required>
                    @error('base_salary')<span style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Lembur</label>
                    <input type="number" name="overtime_pay" class="form-control" value="{{ old('overtime_pay', 0) }}">
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Bonus</label>
                    <input type="number" name="bonus" class="form-control" value="{{ old('bonus', 0) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Potongan</label>
                    <input type="number" name="deductions" class="form-control" value="{{ old('deductions', 0) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Potongan Hutang</label>
                <input type="number" name="debt_deduction" class="form-control" value="{{ old('debt_deduction', 0) }}">
                <small style="color: var(--gray-500);">Potongan otomatis dari hutang karyawan jika ada</small>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>

            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
                <a href="{{ route('admin.payrolls.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('employeeSelect').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const salary = selected.dataset.salary || 0;
    document.getElementById('baseSalary').value = salary;
});
</script>
@endsection
