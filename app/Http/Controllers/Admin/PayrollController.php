<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDebt;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee');

        if ($request->search) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('employee_code', 'like', "%{$request->search}%");
            })->orWhere('payroll_number', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->period_type) {
            $query->where('period_type', $request->period_type);
        }

        if ($request->month) {
            $query->whereMonth('period_start', $request->month);
        }

        if ($request->year) {
            $query->whereYear('period_start', $request->year);
        }

        $payrolls = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('name')->get();

        return view('admin.payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_type' => 'required|in:daily,weekly,monthly',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'base_salary' => 'required|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'debt_deduction' => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::find($request->employee_id);

        $payroll = Payroll::create([
            'employee_id' => $request->employee_id,
            'payroll_number' => Payroll::generatePayrollNumber($request->period_type),
            'period_type' => $request->period_type,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'base_salary' => $request->base_salary,
            'overtime_pay' => $request->overtime_pay ?? 0,
            'bonus' => $request->bonus ?? 0,
            'deductions' => $request->deductions ?? 0,
            'debt_deduction' => $request->debt_deduction ?? 0,
            'notes' => $request->notes,
        ]);

        $payroll->calculateTotal();

        return redirect()->route('admin.payrolls.index')
            ->with('success', 'Data penggajian berhasil dibuat!');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load(['employee', 'approvedBy', 'debtPayments.debt']);

        return view('admin.payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Penggajian yang sudah dibayar tidak dapat diedit!');
        }

        $employees = Employee::active()->orderBy('name')->get();

        return view('admin.payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Penggajian yang sudah dibayar tidak dapat diedit!');
        }

        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'base_salary' => 'required|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'debt_deduction' => 'nullable|numeric|min:0',
        ]);

        $payroll->update([
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'base_salary' => $request->base_salary,
            'overtime_pay' => $request->overtime_pay ?? 0,
            'bonus' => $request->bonus ?? 0,
            'deductions' => $request->deductions ?? 0,
            'debt_deduction' => $request->debt_deduction ?? 0,
            'notes' => $request->notes,
        ]);

        $payroll->calculateTotal();

        return redirect()->route('admin.payrolls.index')
            ->with('success', 'Data penggajian berhasil diperbarui!');
    }

    public function approve(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Hanya penggajian draft yang dapat disetujui!');
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('admin.payrolls.show', $payroll)
            ->with('success', 'Penggajian berhasil disetujui!');
    }

    public function pay(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'approved') {
            return back()->with('error', 'Hanya penggajian yang disetujui yang dapat dibayar!');
        }

        $request->validate([
            'payment_date' => 'required|date',
        ]);

        // Process debt deduction if any
        if ($payroll->debt_deduction > 0) {
            $employee = $payroll->employee;
            $remainingDeduction = $payroll->debt_deduction;

            foreach ($employee->activeDebts as $debt) {
                if ($remainingDeduction <= 0) break;

                $deductAmount = min($remainingDeduction, $debt->remaining_amount);
                $debt->addPayment($deductAmount, $payroll->id, 'salary_deduction');
                $remainingDeduction -= $deductAmount;
            }
        }

        $payroll->update([
            'status' => 'paid',
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->route('admin.payrolls.show', $payroll)
            ->with('success', 'Penggajian berhasil dibayarkan!');
    }

    public function cancel(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Penggajian yang sudah dibayar tidak dapat dibatalkan!');
        }

        $payroll->update(['status' => 'cancelled']);

        return redirect()->route('admin.payrolls.index')
            ->with('success', 'Penggajian berhasil dibatalkan!');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Penggajian yang sudah dibayar tidak dapat dihapus!');
        }

        $payroll->delete();

        return redirect()->route('admin.payrolls.index')
            ->with('success', 'Data penggajian berhasil dihapus!');
    }

    public function report(Request $request)
    {
        $query = Payroll::with('employee')->where('status', 'paid');

        if ($request->period_type) {
            $query->where('period_type', $request->period_type);
        }

        if ($request->start_date) {
            $query->where('period_start', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('period_end', '<=', $request->end_date);
        }

        $payrolls = $query->orderBy('payment_date', 'desc')->get();

        $summary = [
            'total_base' => $payrolls->sum('base_salary'),
            'total_overtime' => $payrolls->sum('overtime_pay'),
            'total_bonus' => $payrolls->sum('bonus'),
            'total_deductions' => $payrolls->sum('deductions'),
            'total_debt_deductions' => $payrolls->sum('debt_deduction'),
            'total_amount' => $payrolls->sum('total_amount'),
        ];

        return view('admin.payrolls.report', compact('payrolls', 'summary'));
    }
}
