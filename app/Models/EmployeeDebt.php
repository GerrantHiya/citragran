<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDebt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'debt_number',
        'amount',
        'paid_amount',
        'remaining_amount',
        'debt_date',
        'reason',
        'description',
        'installment_count',
        'installment_amount',
        'status',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'debt_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payments()
    {
        return $this->hasMany(DebtPayment::class, 'employee_debt_id');
    }

    public function addPayment($amount, $payrollId = null, $type = 'salary_deduction')
    {
        $this->payments()->create([
            'amount' => $amount,
            'payment_date' => now(),
            'payroll_id' => $payrollId,
            'payment_type' => $type,
        ]);

        $this->paid_amount += $amount;
        $this->remaining_amount -= $amount;

        if ($this->remaining_amount <= 0) {
            $this->status = 'paid';
            $this->remaining_amount = 0;
        }

        $this->save();
    }

    public static function generateDebtNumber()
    {
        $prefix = 'DBT';
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return $prefix . $date . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
