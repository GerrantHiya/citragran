@extends('layouts.admin')

@section('title', 'Tambah Hutang')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h3 class="card-title">Form Tambah Hutang Karyawan</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.employee-debts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Karyawan *</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">Pilih Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->employee_code }} - {{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah *</label>
                <input type="number" name="amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi *</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal</label>
                <input type="date" name="debt_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
                <a href="{{ route('admin.employee-debts.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
