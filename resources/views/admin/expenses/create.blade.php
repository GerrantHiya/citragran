@extends('layouts.admin')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header"><h3 class="card-title">Form Pengeluaran</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="expense_category_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal *</label>
                    <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
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
                <label class="form-label">Bukti (Opsional)</label>
                <input type="file" name="receipt" class="form-control" accept="image/*,.pdf">
            </div>
            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
