@extends('layouts.admin')

@section('title', 'Buat Payroll')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header"><h3 class="card-title">Form Penggajian</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.payrolls.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Karyawan *</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">Pilih Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->employee_code }} - {{ $emp->name }} ({{ $emp->type_name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Periode *</label>
                    <select name="period_type" class="form-control" required>
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                        <option value="monthly" selected>Bulanan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Periode *</label>
                    <input type="date" name="period_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Gaji Pokok *</label>
                    <input type="number" name="base_salary" class="form-control" value="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Bonus</label>
                    <input type="number" name="bonus" class="form-control" value="0">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>
            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
                <a href="{{ route('admin.payrolls.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
