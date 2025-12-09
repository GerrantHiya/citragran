<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'total_income',
        'total_expense',
        'total_payroll',
        'balance',
        'summary',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'total_payroll' => 'decimal:2',
        'balance' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPeriodNameAttribute()
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$this->month] . ' ' . $this->year;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public static function generate($month, $year, $userId)
    {
        // Calculate income from IPL payments
        $totalIncome = Payment::whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->sum('amount');

        // Calculate expenses
        $totalExpense = Expense::where('status', 'approved')
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->sum('amount');

        // Calculate payroll
        $totalPayroll = Payroll::where('status', 'paid')
            ->whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->sum('total_amount');

        $balance = $totalIncome - $totalExpense - $totalPayroll;

        return self::updateOrCreate(
            ['month' => $month, 'year' => $year],
            [
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'total_payroll' => $totalPayroll,
                'balance' => $balance,
                'created_by' => $userId,
            ]
        );
    }
}
