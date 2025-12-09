<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'createdBy']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('expense_number', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->category) {
            $query->where('expense_category_id', $request->category);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->start_date) {
            $query->where('expense_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('expense_date', '<=', $request->end_date);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15);
        $categories = ExpenseCategory::active()->get();

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::active()->get();

        return view('admin.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'expense_category_id' => $request->expense_category_id,
            'expense_number' => Expense::generateExpenseNumber(),
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ];

        if ($request->hasFile('receipt_file')) {
            $data['receipt_file'] = $request->file('receipt_file')->store('expenses', 'public');
        }

        Expense::create($data);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function show(Expense $expense)
    {
        $expense->load(['category', 'createdBy', 'approvedBy']);

        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('error', 'Hanya pengeluaran pending yang dapat diedit!');
        }

        $categories = ExpenseCategory::active()->get();

        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('error', 'Hanya pengeluaran pending yang dapat diedit!');
        }

        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'expense_category_id' => $request->expense_category_id,
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'notes' => $request->notes,
        ];

        if ($request->hasFile('receipt_file')) {
            // Delete old file
            if ($expense->receipt_file) {
                Storage::disk('public')->delete($expense->receipt_file);
            }
            $data['receipt_file'] = $request->file('receipt_file')->store('expenses', 'public');
        }

        $expense->update($data);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function approve(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('error', 'Pengeluaran ini sudah diproses!');
        }

        $expense->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('admin.expenses.show', $expense)
            ->with('success', 'Pengeluaran berhasil disetujui!');
    }

    public function reject(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return back()->with('error', 'Pengeluaran ini sudah diproses!');
        }

        $expense->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('admin.expenses.show', $expense)
            ->with('success', 'Pengeluaran berhasil ditolak!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->status === 'approved') {
            return back()->with('error', 'Pengeluaran yang sudah disetujui tidak dapat dihapus!');
        }

        if ($expense->receipt_file) {
            Storage::disk('public')->delete($expense->receipt_file);
        }

        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus!');
    }

    public function report(Request $request)
    {
        $query = Expense::with('category')->where('status', 'approved');

        if ($request->start_date) {
            $query->where('expense_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('expense_date', '<=', $request->end_date);
        }

        if ($request->category) {
            $query->where('expense_category_id', $request->category);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        $byCategory = $expenses->groupBy('expense_category_id')->map(function ($items) {
            return [
                'category' => $items->first()->category->name,
                'total' => $items->sum('amount'),
                'count' => $items->count(),
            ];
        });

        $categories = ExpenseCategory::active()->get();

        return view('admin.expenses.report', compact('expenses', 'byCategory', 'categories'));
    }
}
