<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'payroll_number',
        'period_type',
        'period_start',
        'period_end',
        'base_salary',
        'overtime_pay',
        'bonus',
        'deductions',
        'debt_deduction',
        'total_amount',
        'payment_date',
        'status',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'payment_date' => 'date',
        'base_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'debt_deduction' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    const STATUSES = [
        'draft' => 'Draft',
        'approved' => 'Disetujui',
        'paid' => 'Dibayar',
        'cancelled' => 'Dibatalkan',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function debtPayments()
    {
        return $this->hasMany(DebtPayment::class);
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPeriodNameAttribute()
    {
        return $this->period_start->format('d M Y') . ' - ' . $this->period_end->format('d M Y');
    }

    public function calculateTotal()
    {
        $this->total_amount = $this->base_salary + $this->overtime_pay + $this->bonus - $this->deductions - $this->debt_deduction;
        $this->save();
    }

    public static function generatePayrollNumber($periodType)
    {
        $prefixes = [
            'daily' => 'PRD',
            'weekly' => 'PRW',
            'monthly' => 'PRM',
        ];
        $prefix = $prefixes[$periodType] ?? 'PAY';
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
