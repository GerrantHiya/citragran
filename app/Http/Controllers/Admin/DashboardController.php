<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\IplBill;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\Report;
use App\Models\Resident;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Statistics
        $stats = [
            'total_residents' => Resident::where('status', 'active')->count(),
            'total_employees' => Employee::where('status', 'active')->count(),
            'pending_bills' => IplBill::whereIn('status', ['pending', 'overdue'])->count(),
            'pending_reports' => Report::whereIn('status', ['received', 'analyzing', 'processing'])->count(),
        ];

        // Monthly Income (current month)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $monthlyIncome = Payment::whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->sum('amount');

        $monthlyExpense = Expense::where('status', 'approved')
            ->whereMonth('expense_date', $currentMonth)
            ->whereYear('expense_date', $currentYear)
            ->sum('amount');

        $monthlyPayroll = Payroll::where('status', 'paid')
            ->whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->sum('total_amount');

        // Recent Activities
        $recentBills = IplBill::with('resident')
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with('resident')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with('bill.resident')
            ->latest()
            ->take(5)
            ->get();

        // Overdue Bills
        $overdueBills = IplBill::with('resident')
            ->where('status', 'overdue')
            ->orderBy('due_date')
            ->take(10)
            ->get();

        // Income Chart Data (last 6 months)
        $chartData = $this->getChartData();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyPayroll',
            'recentBills',
            'recentReports',
            'recentPayments',
            'overdueBills',
            'chartData'
        ));
    }

    private function getChartData()
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $months[] = $date->format('M Y');

            $incomeData[] = Payment::whereMonth('payment_date', $month)
                ->whereYear('payment_date', $year)
                ->sum('amount');

            $expenses = Expense::where('status', 'approved')
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->sum('amount');

            $payroll = Payroll::where('status', 'paid')
                ->whereMonth('payment_date', $month)
                ->whereYear('payment_date', $year)
                ->sum('total_amount');

            $expenseData[] = $expenses + $payroll;
        }

        return [
            'labels' => $months,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
}
