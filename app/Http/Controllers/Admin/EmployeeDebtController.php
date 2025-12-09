<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDebt;
use Illuminate\Http\Request;

class EmployeeDebtController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeDebt::with('employee');

        if ($request->search) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('employee_code', 'like', "%{$request->search}%");
            })->orWhere('debt_number', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $debts = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.employee-debts.index', compact('debts'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('name')->get();

        return view('admin.employee-debts.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:1',
            'debt_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string',
            'installment_count' => 'required|integer|min:1',
        ]);

        $installmentAmount = $request->amount / $request->installment_count;

        EmployeeDebt::create([
            'employee_id' => $request->employee_id,
            'debt_number' => EmployeeDebt::generateDebtNumber(),
            'amount' => $request->amount,
            'remaining_amount' => $request->amount,
            'debt_date' => $request->debt_date,
            'reason' => $request->reason,
            'description' => $request->description,
            'installment_count' => $request->installment_count,
            'installment_amount' => $installmentAmount,
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('admin.employee-debts.index')
            ->with('success', 'Data hutang karyawan berhasil ditambahkan!');
    }

    public function show(EmployeeDebt $employeeDebt)
    {
        $employeeDebt->load(['employee', 'payments.payroll', 'approvedBy']);

        return view('admin.employee-debts.show', compact('employeeDebt'));
    }

    public function addPayment(Request $request, EmployeeDebt $employeeDebt)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $employeeDebt->remaining_amount,
            'payment_date' => 'required|date',
        ]);

        $employeeDebt->addPayment($request->amount, null, 'cash');

        return redirect()->route('admin.employee-debts.show', $employeeDebt)
            ->with('success', 'Pembayaran hutang berhasil dicatat!');
    }

    public function cancel(EmployeeDebt $employeeDebt)
    {
        if ($employeeDebt->paid_amount > 0) {
            return back()->with('error', 'Hutang yang sudah ada pembayaran tidak dapat dibatalkan!');
        }

        $employeeDebt->update(['status' => 'cancelled']);

        return redirect()->route('admin.employee-debts.index')
            ->with('success', 'Hutang berhasil dibatalkan!');
    }

    public function destroy(EmployeeDebt $employeeDebt)
    {
        if ($employeeDebt->paid_amount > 0) {
            return back()->with('error', 'Hutang yang sudah ada pembayaran tidak dapat dihapus!');
        }

        $employeeDebt->delete();

        return redirect()->route('admin.employee-debts.index')
            ->with('success', 'Data hutang berhasil dihapus!');
    }
}
