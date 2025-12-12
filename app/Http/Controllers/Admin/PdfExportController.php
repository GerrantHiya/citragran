<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\IplBill;
use App\Models\Payment;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PdfExportController extends Controller
{
    /**
     * Export laporan pendapatan IPL ke PDF
     */
    public function incomeReport(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $payments = Payment::with(['iplBill.resident'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'verified')
            ->orderBy('payment_date', 'desc')
            ->get();

        $totalIncome = $payments->sum('amount');

        return view('admin.pdf.income-report', compact('payments', 'totalIncome', 'startDate', 'endDate'));
    }

    /**
     * Export laporan pengeluaran ke PDF
     */
    public function expenseReport(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $expenses = Expense::with('category')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->orderBy('expense_date', 'desc')
            ->get();

        $totalExpense = $expenses->sum('amount');
        $byCategory = $expenses->groupBy('category.name')->map(fn($items) => $items->sum('amount'));

        return view('admin.pdf.expense-report', compact('expenses', 'totalExpense', 'byCategory', 'startDate', 'endDate'));
    }

    /**
     * Export laporan penggajian ke PDF
     */
    public function payrollReport(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $payrolls = Payroll::with('employee')
            ->where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->orderBy('payment_date', 'desc')
            ->get();

        $totalPayroll = $payrolls->sum('net_salary');

        return view('admin.pdf.payroll-report', compact('payrolls', 'totalPayroll', 'startDate', 'endDate'));
    }

    /**
     * Export tagihan warga ke PDF
     */
    public function billDetail(IplBill $bill)
    {
        $bill->load(['resident', 'items.billingType', 'payments']);
        
        return view('admin.pdf.bill-detail', compact('bill'));
    }
}
