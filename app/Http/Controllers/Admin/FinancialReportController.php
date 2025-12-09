<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\FinancialReport;
use App\Models\IplBill;
use App\Models\Payment;
use App\Models\Payroll;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialReport::with('createdBy');

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $reports = $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        $years = FinancialReport::distinct()->pluck('year');

        return view('admin.financial-reports.index', compact('reports', 'years'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        $report = FinancialReport::generate(
            $request->month,
            $request->year,
            auth()->id()
        );

        return redirect()->route('admin.financial-reports.show', $report)
            ->with('success', 'Laporan keuangan berhasil dibuat!');
    }

    public function show(FinancialReport $financialReport)
    {
        // Income details
        $payments = Payment::with('bill.resident')
            ->whereMonth('payment_date', $financialReport->month)
            ->whereYear('payment_date', $financialReport->year)
            ->get();

        // Expense details
        $expenses = Expense::with('category')
            ->where('status', 'approved')
            ->whereMonth('expense_date', $financialReport->month)
            ->whereYear('expense_date', $financialReport->year)
            ->get();

        // Payroll details
        $payrolls = Payroll::with('employee')
            ->where('status', 'paid')
            ->whereMonth('payment_date', $financialReport->month)
            ->whereYear('payment_date', $financialReport->year)
            ->get();

        // Bill collection stats
        $billStats = [
            'total' => IplBill::where('month', $financialReport->month)
                ->where('year', $financialReport->year)
                ->count(),
            'paid' => IplBill::where('month', $financialReport->month)
                ->where('year', $financialReport->year)
                ->where('status', 'paid')
                ->count(),
            'pending' => IplBill::where('month', $financialReport->month)
                ->where('year', $financialReport->year)
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->count(),
        ];

        return view('admin.financial-reports.show', compact(
            'financialReport',
            'payments',
            'expenses',
            'payrolls',
            'billStats'
        ));
    }

    public function publish(FinancialReport $financialReport)
    {
        $financialReport->update([
            'is_published' => true,
        ]);

        return redirect()->route('admin.financial-reports.show', $financialReport)
            ->with('success', 'Laporan berhasil dipublikasikan!');
    }

    public function unpublish(FinancialReport $financialReport)
    {
        $financialReport->update([
            'is_published' => false,
        ]);

        return redirect()->route('admin.financial-reports.show', $financialReport)
            ->with('success', 'Laporan berhasil di-unpublish!');
    }

    public function updateSummary(Request $request, FinancialReport $financialReport)
    {
        $request->validate([
            'summary' => 'nullable|string',
        ]);

        $financialReport->update([
            'summary' => $request->summary,
        ]);

        return redirect()->route('admin.financial-reports.show', $financialReport)
            ->with('success', 'Ringkasan laporan berhasil diperbarui!');
    }

    public function incomeReport(Request $request)
    {
        $query = Payment::with('bill.resident');

        if ($request->start_date) {
            $query->where('payment_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('payment_date', '<=', $request->end_date);
        }

        $payments = $query->orderBy('payment_date', 'desc')->get();

        $byMethod = $payments->groupBy('payment_method')->map(function ($items) {
            return $items->sum('amount');
        });

        return view('admin.financial-reports.income', compact('payments', 'byMethod'));
    }
}
